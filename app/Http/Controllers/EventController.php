<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class EventController extends Controller
{
    public function publicIndex(Request $request)
    {
        $events = Event::with(['category', 'booths'])
            ->where('status', Event::STATUS_PUBLISHED)
            ->withCount([
                'booths',
                'booths as available_booths_count' => function ($query) {
                    $query->where('status', 'available');
                }
            ])
            ->latest('start_time')
            ->paginate(12);

        return view('events.index', [
            'events' => $events,
        ]);
    }

    public function publicShow(Event $event)
    {
        // Only show published events
        if ($event->status !== Event::STATUS_PUBLISHED) {
            abort(404, 'Event not found or not available');
        }

        $event->load([
            'category',
            'user',
            'booths' => function ($query) {
                $query->orderBy('number');
            },
            'ratings' => function ($query) {
                $query->where('rating_type', 'event')
                    ->with('user')
                    ->latest()
                    ->limit(10);
            }
        ]);

        // Calculate average rating
        $averageRating = $event->ratings()
            ->where('rating_type', 'event')
            ->avg('rating');

        $totalReviews = $event->ratings()
            ->where('rating_type', 'event')
            ->count();

        // Get booth statistics
        $totalBooths = $event->booths()->count();
        $availableBooths = $event->booths()->where('status', 'available')->count();
        $bookedBooths = $event->booths()->where('status', 'booked')->count();

        // Get price range from booth configuration
        $boothConfig = $event->booth_configuration;
        $prices = [];
        foreach ($boothConfig as $type => $config) {
            if (isset($config['price'])) {
                $prices[] = $config['price'];
            }
        }
        $minPrice = !empty($prices) ? min($prices) : 0;
        $maxPrice = !empty($prices) ? max($prices) : 0;

        return view('events.details', [
            'event' => $event,
            'averageRating' => $averageRating ? round($averageRating, 1) : 0,
            'totalReviews' => $totalReviews,
            'totalBooths' => $totalBooths,
            'availableBooths' => $availableBooths,
            'bookedBooths' => $bookedBooths,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
        ]);
    }

    public function showBooths(Event $event)
    {
        // Only show published events
        if ($event->status !== Event::STATUS_PUBLISHED) {
            abort(404, 'Event not found or not available');
        }

        $event->load([
            'category',
            'user',
            'booths' => function ($query) {
                $query->orderBy('number');
            }
        ]);

        // Get booth statistics
        $totalBooths = $event->booths()->count();
        $availableBooths = $event->booths()->where('status', 'available')->count();

        // Get price range from booth configuration
        $boothConfig = $event->booth_configuration;
        $prices = [];
        foreach ($boothConfig as $type => $config) {
            if (isset($config['price'])) {
                $prices[] = $config['price'];
            }
        }
        $minPrice = !empty($prices) ? min($prices) : 0;
        $maxPrice = !empty($prices) ? max($prices) : 0;

        return view('booths.index', [
            'event' => $event,
            'totalBooths' => $totalBooths,
            'availableBooths' => $availableBooths,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
        ]);
    }

    public function showBoothDetails($boothId)
    {
        $booth = \App\Models\Booth::with(['event.category', 'event.user'])
            ->findOrFail($boothId);

        $event = $booth->event;

        // Only show if event is published
        if ($event->status !== Event::STATUS_PUBLISHED) {
            abort(404, 'Event not found or not available');
        }

        // Check if booth is available
        if ($booth->status !== 'available') {
            return redirect()->route('booths.index', $event->id)
                ->with('error', 'This booth is not available for booking');
        }

        return view('booths.details', compact('booth', 'event'));
    }

    public function index(Request $request)
    {
        $events = Event::with([
            'category',
            'booths',
            'bookings' => function ($query) {
                $query->where('bookings.status', '!=', 'cancelled');
            }
        ])
            ->withCount([
                'booths',
                'bookings as booked_booths_count' => function ($query) {
                    $query->where('bookings.status', '!=', 'cancelled');
                }
            ])
            ->ownedBy($request->user())
            ->latest('created_at')
            ->paginate(9);

        return view('my-events.index', [
            'events' => $events,
        ]);
    }

    public function create(Request $request)
    {
        $categories = Category::orderBy('name')->get();

        return view('my-events.create', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $action = $request->input('action', 'draft');
        $data = $this->validatePayload($request, $action);

        $event = new Event();
        $this->applyPayload($event, $data, $request->user(), $action);

        if ($action === 'publish') {
            return redirect()
                ->route('booth-layout', ['event_id' => $event->id])
                ->with('status', 'Event created as draft. Set up the booth layout to finalize your event.');
        }

        if ($action === 'create_layout') {
            return redirect()
                ->route('booth-layout', ['event_id' => $event->id])
                ->with('status', 'Event created successfully! Now design your booth layout to finalize your event.');
        }

        return redirect()
            ->route('my-events.index')
            ->with('status', 'Draft saved successfully.');
    }

    public function show(Request $request, Event $event)
    {
        $this->ensureOwnership($request, $event);

        $event->load([
            'category',
            'booths' => function ($query) {
                $query->orderBy('number');
            }
        ]);

        return view('my-events.details', [
            'event' => $event,
        ]);
    }

    public function edit(Request $request, Event $event)
    {
        $this->ensureOwnership($request, $event);

        $categories = Category::orderBy('name')->get();

        return view('my-events.edit', [
            'event' => $event,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Event $event)
    {
        $this->ensureOwnership($request, $event);

        $action = $request->input('action', 'save');
        $data = $this->validatePayload($request, $action === 'save' ? ($event->isPublished() ? 'publish' : 'draft') : $action);

        $this->applyPayload($event, $data, $request->user(), $action);

        $statusMessage = $action === 'publish'
            ? 'Event updated and published.'
            : ($action === 'save' ? 'Event updated successfully.' : 'Draft updated successfully.');

        return redirect()
            ->route('my-events.index')
            ->with('status', $statusMessage);
    }

    public function publish(Request $request, Event $event)
    {
        $this->ensureOwnership($request, $event);

        // Only allow publishing if the event is finalized
        if ($event->status !== Event::STATUS_FINALIZED) {
            return redirect()
                ->back()
                ->with('error', 'Only finalized events can be published.');
        }

        $event->status = Event::STATUS_PUBLISHED;
        $event->save();

        return redirect()
            ->route('my-events.show', $event)
            ->with('status', 'Event published successfully!');
    }

    public function destroy(Request $request, Event $event)
    {
        $this->ensureOwnership($request, $event);

        $event->delete();

        return redirect()
            ->route('my-events.index')
            ->with('status', 'Event deleted successfully.');
    }

    protected function validatePayload(Request $request, string $action): array
    {
        $requiresFullValidation = in_array($action, ['publish', 'create_layout']);

        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => [$requiresFullValidation ? 'required' : 'nullable', 'integer', 'exists:categories,id'],
            'capacity' => ['nullable', 'integer', 'min:0'],
            'venue' => [$requiresFullValidation ? 'required' : 'nullable', 'string', 'max:255'],
            'city' => [$requiresFullValidation ? 'required' : 'nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'start_date' => [$requiresFullValidation ? 'required' : 'nullable', 'date'],
            'start_time' => [$requiresFullValidation ? 'required' : 'nullable', 'date_format:H:i'],
            'end_date' => [$requiresFullValidation ? 'required' : 'nullable', 'date', 'after_or_equal:start_date'],
            'end_time' => [$requiresFullValidation ? 'required' : 'nullable', 'date_format:H:i'],
            'registration_deadline' => ['nullable', 'date'],
            'booth_standard_size' => ['nullable', 'string', 'max:50'],
            'booth_standard_price' => ['nullable', 'integer', 'min:0'],
            'booth_standard_qty' => ['nullable', 'integer', 'min:0'],
            'booth_premium_size' => ['nullable', 'string', 'max:50'],
            'booth_premium_price' => ['nullable', 'integer', 'min:0'],
            'booth_premium_qty' => ['nullable', 'integer', 'min:0'],
            'booth_vip_size' => ['nullable', 'string', 'max:50'],
            'booth_vip_price' => ['nullable', 'integer', 'min:0'],
            'booth_vip_qty' => ['nullable', 'integer', 'min:0'],
            'confirm_terms' => $requiresFullValidation ? ['accepted'] : ['nullable'],
        ];

        $messages = [
            'confirm_terms.accepted' => 'Please confirm that all event details are accurate before creating the event.',
        ];

        return $request->validate($rules, $messages);
    }

    protected function applyPayload(Event $event, array $data, $user, string $action): void
    {
        $event->fill([
            'category_id' => $data['category_id'] ?? null,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'capacity' => $data['capacity'] ?? null,
        ]);

        $event->start_time = $this->combineDateAndTime($data['start_date'] ?? null, $data['start_time'] ?? null);
        $event->end_time = $this->combineDateAndTime($data['end_date'] ?? null, $data['end_time'] ?? null);

        $event->location = array_filter([
            'venue' => $data['venue'] ?? null,
            'city' => $data['city'] ?? null,
            'address' => $data['address'] ?? null,
            'registration_deadline' => $data['registration_deadline'] ?? null,
            'booths' => $this->extractBoothConfig($data),
        ], function ($value) {
            if (is_array($value)) {
                return !empty($value);
            }

            return filled($value);
        });

        // Determine status based on action
        // New events start as DRAFT until booths are configured
        // Only set to PUBLISHED if it's an update action and was already published
        if ($action === 'save') {
            // Keep the existing status unless it hasn't been set before
            $event->status = $event->status ?: Event::STATUS_DRAFT;
        } elseif (in_array($action, ['publish', 'create_layout'])) {
            // If the event already exists and has booths, keep published status
            // Otherwise, start as draft until booth layout is saved
            if ($event->exists && $event->booths()->count() > 0) {
                $event->status = Event::STATUS_PUBLISHED;
            } else {
                $event->status = Event::STATUS_DRAFT;
            }
        } else {
            $event->status = Event::STATUS_DRAFT;
        }

        $event->user_id = $event->user_id ?: $user->id;

        $event->save();
    }

    protected function extractBoothConfig(array $data): array
    {
        $types = ['standard', 'premium', 'vip'];
        $booths = [];

        foreach ($types as $type) {
            $config = array_filter([
                'size' => $data['booth_' . $type . '_size'] ?? null,
                'price' => $data['booth_' . $type . '_price'] ?? null,
                'qty' => $data['booth_' . $type . '_qty'] ?? null,
            ], fn($value) => filled($value));

            if (!empty($config)) {
                $booths[$type] = $config;
            }
        }

        return $booths;
    }

    protected function combineDateAndTime(?string $date, ?string $time): ?Carbon
    {
        if (!$date) {
            return null;
        }

        $time = $time ?: '00:00';

        return Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $time);
    }

    protected function ensureOwnership(Request $request, Event $event): void
    {
        if ($event->user_id !== $request->user()->id) {
            abort(403);
        }
    }

    /**
     * Update event status to finalized when booths are properly set up
     */
    public function updateStatusToFinalized(Event $event): void
    {
        if ($event->isDraft() && $event->canBeFinalized()) {
            $event->update(['status' => Event::STATUS_FINALIZED]);
        }
    }
}
