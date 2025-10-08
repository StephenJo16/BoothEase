<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class EventController extends Controller
{
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
                ->route('testing-layout', ['event_id' => $event->id])
                ->with('status', 'Event published successfully. Set up the booth layout next.');
        }

        if ($action === 'create_layout') {
            return redirect()
                ->route('testing-layout', ['event_id' => $event->id])
                ->with('status', 'Event created successfully! Now design your booth layout.');
        }

        return redirect()
            ->route('my-events.index')
            ->with('status', 'Draft saved successfully.');
    }

    public function show(Request $request, Event $event)
    {
        $this->ensureOwnership($request, $event);

        return view('my-events.details', [
            'event' => $event->load('category'),
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

        $action = $request->input('action', $event->isPublished() ? 'publish' : 'draft');
        $data = $this->validatePayload($request, $action);

        $this->applyPayload($event, $data, $request->user(), $action);

        return redirect()
            ->route('my-events.index')
            ->with('status', $action === 'publish' ? 'Event updated and published.' : 'Draft updated successfully.');
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
        if (in_array($action, ['publish', 'create_layout'])) {
            $event->status = Event::STATUS_PUBLISHED;
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
