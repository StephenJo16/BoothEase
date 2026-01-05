<?php

namespace App\Http\Controllers;

use App\Events\EventPublished;
use App\Models\Category;
use App\Models\Event;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\Subdistrict;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function publicIndex(Request $request)
    {
        // Restrict access for event organizers
        if (Auth::check() && Auth::user()->role->name === 'event_organizer') {
            return redirect()->route('my-events.index');
        }

        // Update event statuses before loading
        $this->updateEventStatuses();

        $now = now();

        // Get filter parameters
        $search = $request->input('search');
        $categories = $request->input('categories', []);
        $provinceId = $request->input('province_id');
        $cityId = $request->input('city_id');
        $refundable = $request->input('refundable');

        // Base query with filters
        $baseQuery = function ($query) use ($search, $categories, $provinceId, $cityId, $refundable) {
            // Search filter
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%')
                        ->orWhere('venue', 'like', '%' .  $search . '%')
                        ->orWhere('address', 'like', '%' . $search . '%')
                        ->orWhereHas('city', function ($q) use ($search) {
                            $q->where('name', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('province', function ($q) use ($search) {
                            $q->where('name', 'like', '%' . $search . '%');
                        });
                });
            }

            // Category filter
            if (!empty($categories)) {
                $query->whereIn('category_id', $categories);
            }

            // Province filter
            if ($provinceId) {
                $query->where('province_id', $provinceId);
            }

            // City filter
            if ($cityId) {
                $query->where('city_id', $cityId);
            }

            // Refundable filter
            if ($refundable) {
                $query->where('refundable', true);
            }
        };

        // 1. Published events where registration is still open (up to registration_deadline datetime)
        $openForRegistration = Event::with(['category', 'booths'])
            ->where('status', Event::STATUS_PUBLISHED)
            ->where(function ($query) use ($now) {
                $query->where('registration_deadline', '>=', $now)
                    ->orWhereNull('registration_deadline');
            })
            ->where('start_time', '>', $now) // Not started yet
            ->where($baseQuery)
            ->withCount([
                'booths',
                'booths as available_booths_count' => function ($query) {
                    $query->where('status', 'available');
                }
            ])
            ->latest('start_time')
            ->get();

        // 2. Published events past registration deadline but not started yet
        $registrationClosed = Event::with(['category', 'booths'])
            ->where('status', Event::STATUS_PUBLISHED)
            ->where('registration_deadline', '<', $now)
            ->where('start_time', '>', $now) // Not started yet
            ->where($baseQuery)
            ->withCount([
                'booths',
                'booths as available_booths_count' => function ($query) {
                    $query->where('status', 'available');
                }
            ])
            ->latest('start_time')
            ->get();

        // 3. Ongoing events (started but not completed)
        $ongoingEvents = Event::with(['category', 'booths'])
            ->where(function ($query) {
                $query->where('status', Event::STATUS_ONGOING)
                    ->orWhere('status', Event::STATUS_PUBLISHED);
            })
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->where($baseQuery)
            ->withCount([
                'booths',
                'booths as available_booths_count' => function ($query) {
                    $query->where('status', 'available');
                }
            ])
            ->latest('start_time')
            ->get();

        // 4. Completed events
        $completedEvents = Event::with(['category', 'booths'])
            ->where(function ($query) {
                $query->where('status', Event::STATUS_COMPLETED)
                    ->orWhere('status', Event::STATUS_PUBLISHED)
                    ->orWhere('status', Event::STATUS_ONGOING);
            })
            ->where('end_time', '<', $now)
            ->where($baseQuery)
            ->withCount([
                'booths',
                'booths as available_booths_count' => function ($query) {
                    $query->where('status', 'available');
                }
            ])
            ->latest('end_time')
            ->get();

        // Get all categories for filter dropdown
        $allCategories = Category::orderBy('name')->get();

        // Get all provinces for filter dropdown
        $allProvinces = Province::orderBy('name')->get();

        // Get cities based on selected province
        $allCities = $provinceId ? City::where('province_id', $provinceId)->orderBy('name')->get() : collect();

        return view('events.index', [
            'openForRegistration' => $openForRegistration,
            'registrationClosed' => $registrationClosed,
            'ongoingEvents' => $ongoingEvents,
            'completedEvents' => $completedEvents,
            'allCategories' => $allCategories,
            'allProvinces' => $allProvinces,
            'allCities' => $allCities,
            'filters' => [
                'search' => $search,
                'categories' => $categories,
                'province_id' => $provinceId,
                'city_id' => $cityId,
                'refundable' => $refundable,
            ],
        ]);
    }

    public function publicShow(Event $event)
    {
        // Restrict access for event organizers
        if (Auth::check() && Auth::user()->role->name === 'event_organizer') {
            return redirect()->route('my-events.index');
        }

        // Only show published, ongoing, or completed events (not draft or finalized)
        if (!in_array($event->status, [Event::STATUS_PUBLISHED, Event::STATUS_ONGOING, Event::STATUS_COMPLETED])) {
            abort(404, 'Event not found or not available');
        }

        $event->load([
            'category',
            'user'
        ]);

        // Paginate booths
        $perPage = request('perPage', 5);
        $booths = $event->booths()->orderBy('floor_number')->orderByRaw('LENGTH(name), name')->paginate($perPage);
        $event->setRelation('booths', $booths);

        // Load all ratings for the organizer (across all events)
        $organizerRatings = \App\Models\Rating::with(['rater', 'ratee', 'event'])
            ->where('ratee_id', $event->user_id)
            ->latest()
            ->limit(10)
            ->get();

        // Calculate average rating for the organizer (across all events)
        $averageRating = \App\Models\Rating::where('ratee_id', $event->user_id)
            ->avg('rating');

        $totalReviews = \App\Models\Rating::where('ratee_id', $event->user_id)
            ->count();

        // Get booth statistics
        $totalBooths = $event->booths()->count();
        $availableBooths = $event->booths()->where('status', 'available')->count();
        $bookedBooths = $event->booths()->where('status', 'booked')->count();

        // Get price range from booth configuration
        $boothConfig = $event->booth_configuration ?? [];
        $prices = [];
        foreach ($boothConfig as $type => $config) {
            if (isset($config['price'])) {
                $prices[] = $config['price'];
            }
        }
        $minPrice = !empty($prices) ? min($prices) : 0;
        $maxPrice = !empty($prices) ? max($prices) : 0;

        // Check if registration is still open (up to the deadline datetime)
        $now = now();
        $isRegistrationOpen = is_null($event->registration_deadline) || $event->registration_deadline >= $now;

        $completedEvents = Event::where('user_id', $event->user_id)
            ->where('status', Event::STATUS_COMPLETED)
            ->count();

        $completedBookings = \App\Models\Booking::whereHas('booth.event', function ($query) use ($event) {
            $query->where('user_id', $event->user_id);
        })
            ->where('status', 'completed')
            ->count();

        return view('events.details', [
            'event' => $event,
            'organizerRatings' => $organizerRatings,
            'averageRating' => $averageRating ? round($averageRating, 1) : 0,
            'totalReviews' => $totalReviews,
            'totalBooths' => $totalBooths,
            'availableBooths' => $availableBooths,
            'bookedBooths' => $bookedBooths,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'isRegistrationOpen' => $isRegistrationOpen,
            'completedEvents' => $completedEvents,
            'completedBookings' => $completedBookings,
        ]);
    }

    public function showBooths(Event $event)
    {
        // Restrict access for event organizers
        if (Auth::check() && Auth::user()->role->name === 'event_organizer') {
            return redirect()->route('my-events.index');
        }

        // Only show published events
        if ($event->status !== Event::STATUS_PUBLISHED) {
            abort(404, 'Event not found or not available');
        }

        $event->load([
            'category',
            'user',
            'booths' => function ($query) {
                $query->orderBy('name');
            }
        ]);

        // Get booth statistics
        $totalBooths = $event->booths()->count();
        $availableBooths = $event->booths()->where('status', 'available')->count();

        // Get price range from booth configuration
        $boothConfig = $event->booth_configuration ?? [];
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
        // Restrict access for event organizers
        if (Auth::check() && Auth::user()->role->name === 'event_organizer') {
            return redirect()->route('my-events.index');
        }

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
        // Update event statuses before loading
        $this->updateEventStatuses();

        // Get filter parameters
        $search = $request->input('search');
        $categories = $request->input('categories', []);
        $statuses = $request->input('statuses', []);
        $provinceId = $request->input('province_id');
        $cityId = $request->input('city_id');
        $refundable = $request->input('refundable');

        // Build query with filters
        $query = Event::with([
            'category',
            'booths',
            'bookings' => function ($query) {
                $query->where('bookings.status', '!=', 'cancelled');
            }
        ])
            ->withCount([
                'booths',
                'booths as booked_booths_count' => function ($query) {
                    $query->where('status', 'booked');
                },
                'booths as available_booths_count' => function ($query) {
                    $query->where('status', 'available');
                }
            ])
            ->ownedBy($request->user());

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('venue', 'like', '%' . $search . '%')
                    ->orWhere('address', 'like', '%' . $search . '%')
                    ->orWhereHas('city', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('province', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        // Category filter
        if (!empty($categories)) {
            $query->whereIn('category_id', $categories);
        }

        // Status filter
        if (!empty($statuses)) {
            $query->whereIn('status', $statuses);
        }

        // Province filter
        if ($provinceId) {
            $query->where('province_id', $provinceId);
        }

        // City filter
        if ($cityId) {
            $query->where('city_id', $cityId);
        }

        // Refundable filter
        if ($refundable) {
            $query->where('refundable', true);
        }

        $events = $query->latest('created_at')->paginate(9);

        // Get all categories for filter dropdown
        $allCategories = Category::orderBy('name')->get();

        // Get all provinces for filter dropdown
        $allProvinces = Province::orderBy('name')->get();

        // Get cities based on selected province
        $allCities = $provinceId ? City::where('province_id', $provinceId)->orderBy('name')->get() : collect();

        return view('my-events.index', [
            'events' => $events,
            'allCategories' => $allCategories,
            'allProvinces' => $allProvinces,
            'allCities' => $allCities,
            'filters' => [
                'search' => $search,
                'categories' => $categories,
                'statuses' => $statuses,
                'province_id' => $provinceId,
                'city_id' => $cityId,
                'refundable' => $refundable,
            ],
        ]);
    }

    public function create(Request $request)
    {
        $categories = Category::orderBy('name')->get();
        $provinces = Province::orderBy('name')->get();
        $cities = collect();
        $districts = collect();
        $subdistricts = collect();

        // If returning with validation errors, load dependent data
        if (old('province_id')) {
            $cities = City::where('province_id', old('province_id'))->orderBy('name')->get();
        }
        if (old('city_id')) {
            $districts = District::where('city_id', old('city_id'))->orderBy('name')->get();
        }
        if (old('district_id')) {
            $subdistricts = Subdistrict::where('district_id', old('district_id'))->orderBy('name')->get();
        }

        return view('my-events.create', [
            'categories' => $categories,
            'provinces' => $provinces,
            'cities' => $cities,
            'districts' => $districts,
            'subdistricts' => $subdistricts,
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
                $query->with('bookings.user')
                    ->orderBy('name');
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
        $provinces = Province::orderBy('name')->get();
        $cities = $event->province_id ? City::where('province_id', $event->province_id)->orderBy('name')->get() : collect();
        $districts = $event->city_id ? District::where('city_id', $event->city_id)->orderBy('name')->get() : collect();
        $subdistricts = $event->district_id ? Subdistrict::where('district_id', $event->district_id)->orderBy('name')->get() : collect();

        return view('my-events.edit', [
            'event' => $event,
            'categories' => $categories,
            'provinces' => $provinces,
            'cities' => $cities,
            'districts' => $districts,
            'subdistricts' => $subdistricts,
        ]);
    }

    public function update(Request $request, Event $event)
    {
        $this->ensureOwnership($request, $event);

        $action = $request->input('action', 'save');
        $data = $this->validatePayload($request, $action === 'save' ? ($event->isPublished() ? 'publish' : 'draft') : $action);

        // Additional validation: if event has no image and no new image is uploaded, reject
        if (!$event->image_path && !$request->hasFile('image')) {
            return redirect()
                ->back()
                ->withErrors(['image' => 'Please upload an event image. This event currently has no image.'])
                ->withInput();
        }

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

        // Validate that the number of booths matches the capacity
        $boothCount = $event->booths()->count();
        $capacity = $event->capacity;

        if ($capacity && $boothCount !== $capacity) {
            return redirect()
                ->back()
                ->with('error', "Cannot publish event. The number of booths ($boothCount) must match the event capacity ($capacity).");
        }

        // Validate that the registration deadline is at least 1 day from today
        if ($event->registration_deadline) {
            $tomorrow = now()->addDay()->startOfDay();
            if ($event->registration_deadline->lt($tomorrow)) {
                return redirect()
                    ->back()
                    ->with('error', 'Cannot publish event. The registration deadline must be at least 1 day from today. Please update the event details.');
            }
        }

        $event->status = Event::STATUS_PUBLISHED;
        $event->save();

        // Dispatch event to notify matching tenants
        EventPublished::dispatch($event);

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
            'image' => [$requiresFullValidation ? 'required' : 'nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif,svg', 'max:5120'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => [$requiresFullValidation ? 'required' : 'nullable', 'integer', 'exists:categories,id'],
            'capacity' => ['nullable', 'integer', 'min:0'],
            'province_id' => ['nullable', 'integer', 'exists:provinces,id'],
            'city_id' => ['nullable', 'integer', 'exists:cities,id'],
            'district_id' => ['nullable', 'integer', 'exists:districts,id'],
            'subdistrict_id' => ['nullable', 'integer', 'exists:subdistricts,id'],
            'venue' => [$requiresFullValidation ? 'required' : 'nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'start_date' => [$requiresFullValidation ? 'required' : 'nullable', 'date'],
            'start_time' => [$requiresFullValidation ? 'required' : 'nullable', 'date_format:H:i'],
            'end_date' => [$requiresFullValidation ? 'required' : 'nullable', 'date', 'after_or_equal:start_date'],
            'end_time' => [$requiresFullValidation ? 'required' : 'nullable', 'date_format:H:i'],
            'registration_deadline' => ['nullable', 'date'],
            'registration_deadline_time' => ['nullable', 'date_format:H:i'],
            'refundable' => ['nullable', 'boolean'],
            'terms_and_conditions' => [$requiresFullValidation ? 'required' : 'nullable', 'url', 'max:500'],
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
            'confirm_terms.accepted' => 'You must check the confirmation box at the bottom of the form before proceeding to set up booths.',
            'image.required' => 'Please upload an event image before proceeding.',
            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, webp, gif, or svg.',
            'image.max' => 'The image size must not exceed 5MB.',
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
            'province_id' => $data['province_id'] ?? null,
            'city_id' => $data['city_id'] ?? null,
            'district_id' => $data['district_id'] ?? null,
            'subdistrict_id' => $data['subdistrict_id'] ?? null,
            'venue' => $data['venue'] ?? null,
            'address' => $data['address'] ?? null,
            'refundable' => $data['refundable'] ?? false,
            'terms_and_conditions' => $data['terms_and_conditions'] ?? null,
        ]);

        $event->start_time = $this->combineDateAndTime($data['start_date'] ?? null, $data['start_time'] ?? null);
        $event->end_time = $this->combineDateAndTime($data['end_date'] ?? null, $data['end_time'] ?? null);
        $event->registration_deadline = $this->combineDateAndTime($data['registration_deadline'] ?? null, $data['registration_deadline_time'] ?? null);

        // Handle image upload
        if (request()->hasFile('image')) {
            $image = request()->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('events', $imageName, 'public');
            $event->image_path = $imagePath;
        }

        // Store booth configuration
        $boothConfig = $this->extractBoothConfig($data);
        if (!empty($boothConfig)) {
            $event->booth_configuration = $boothConfig;
        }

        // Determine status based on action
        // New events start as DRAFT until booths are configured
        // Only set to PUBLISHED if it's an update action and was already published
        $wasPublished = $event->exists && $event->status === Event::STATUS_PUBLISHED;

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

        // Dispatch event notification if event was just published (not already published)
        if (!$wasPublished && $event->status === Event::STATUS_PUBLISHED) {
            EventPublished::dispatch($event);
        }
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

    /**
     * Update event statuses based on current date and time
     */
    private function updateEventStatuses(): void
    {
        $now = now();

        // Update events to 'ongoing' status
        Event::where('status', '!=', Event::STATUS_COMPLETED)
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->update(['status' => Event::STATUS_ONGOING]);

        // Update events to 'completed' status
        Event::where('status', '!=', Event::STATUS_COMPLETED)
            ->where('end_time', '<', $now)
            ->update(['status' => Event::STATUS_COMPLETED]);
    }
}
