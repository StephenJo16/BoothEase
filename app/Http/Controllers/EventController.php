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
        $events = Event::with('category')
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

        return redirect()
            ->route('my-events.index')
            ->with('status', $action === 'publish' ? 'Event published successfully.' : 'Draft saved successfully.');
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

        $action = $request->input('action', $event->status === 'published' ? 'publish' : 'draft');
        $data = $this->validatePayload($request, $action);

        $this->applyPayload($event, $data, $request->user(), $action);

        return redirect()
            ->route('my-events.index')
            ->with('status', $action === 'publish' ? 'Event updated and published.' : 'Draft updated successfully.');
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
        $publish = $action === 'publish';

        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => [$publish ? 'required' : 'nullable', 'integer', 'exists:categories,id'],
            'capacity' => ['nullable', 'integer', 'min:0'],
            'venue' => [$publish ? 'required' : 'nullable', 'string', 'max:255'],
            'city' => [$publish ? 'required' : 'nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'start_date' => [$publish ? 'required' : 'nullable', 'date'],
            'start_time' => [$publish ? 'required' : 'nullable', 'date_format:H:i'],
            'end_date' => [$publish ? 'required' : 'nullable', 'date', 'after_or_equal:start_date'],
            'end_time' => [$publish ? 'required' : 'nullable', 'date_format:H:i'],
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
            'confirm_terms' => $publish ? ['accepted'] : ['nullable'],
        ];

        $messages = [
            'confirm_terms.accepted' => 'Please confirm that all event details are accurate before publishing.',
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

        $event->status = $action === 'publish' ? 'published' : 'draft';
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
            ], fn ($value) => filled($value));

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
}

