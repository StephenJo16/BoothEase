<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all bookings with related booth and event data
        $bookings = Booking::with(['booth.event.category', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $totalBookings = $bookings->count();
        $approvedBookings = $bookings->where('status', 'confirmed')->count();
        $totalSpent = $bookings->where('status', 'confirmed')->sum('total_price');

        // Group bookings by status for filtering
        $bookingsByStatus = [
            'all' => $bookings,
            'approved' => $bookings->where('status', 'confirmed'),
            'rejected' => $bookings->where('status', 'rejected'),
            'cancelled' => $bookings->where('status', 'cancelled'),
        ];

        return view('my-bookings.index', compact(
            'bookings',
            'totalBookings',
            'approvedBookings',
            'totalSpent',
            'bookingsByStatus'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $boothId = request('booth_id');

        if (!$boothId) {
            return redirect()->route('events.index')->with('error', 'No booth selected');
        }

        $booth = \App\Models\Booth::with(['event.category', 'event.user'])
            ->findOrFail($boothId);

        // Check if booth is available
        if ($booth->status !== 'available') {
            return redirect()->back()->with('error', 'This booth is not available for booking');
        }

        $event = $booth->event;

        return view('book-booth.index', compact('booth', 'event'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingRequest $request)
    {
        try {
            // Get validated data
            $validated = $request->validated();

            // Get the booth
            $booth = \App\Models\Booth::with('event')->findOrFail($validated['booth_id']);

            // Check if booth is still available
            if ($booth->status !== 'available') {
                return redirect()->back()
                    ->with('error', 'Sorry, this booth is no longer available.')
                    ->withInput();
            }

            // Get or create user based on email
            $fullName = $validated['first_name'] . ' ' . $validated['last_name'];
            $user = \App\Models\User::where('email', $validated['email'])->first();

            if (!$user) {
                // Get default role (user role) - usually role_id = 2 for regular users
                $defaultRole = \App\Models\Role::where('name', 'user')->first();
                if (!$defaultRole) {
                    $defaultRole = \App\Models\Role::first(); // Fallback to first role if 'user' role doesn't exist
                }

                $user = \App\Models\User::create([
                    'role_id' => $defaultRole->id,
                    'name' => $validated['business_name'],
                    'display_name' => $fullName,
                    'email' => $validated['email'],
                    'phone_number' => $validated['phone'],
                    'business_category' => 'General', // Default category
                    'password' => Hash::make(Str::random(16)), // Generate random password
                ]);
            }

            // Create booking
            $booking = Booking::create([
                'user_id' => $user->id,
                'booth_id' => $booth->id,
                'status' => 'pending',
                'booking_date' => now(),
                'total_price' => $booth->price,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Update booth status to booked
            $booth->update(['status' => 'booked']);

            return redirect()->route('my-bookings')
                ->with('success', 'Booking request submitted successfully! Your booking is pending confirmation. We will contact you shortly.');
        } catch (\Exception $e) {
            Log::error('Booking creation failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'An error occurred while processing your booking. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        // Load relationships
        $booking->load([
            'booth.event.category',
            'booth.event.user',
            'user',
            'payment'
        ]);

        return view('my-bookings.details', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        //
    }

    /**
     * Display booking requests for an event (for event organizers)
     */
    public function bookingRequests(Request $request, $eventId)
    {
        // Get the event with its bookings
        $event = \App\Models\Event::with([
            'booths.bookings' => function ($query) {
                $query->with(['user', 'booth'])
                    ->orderBy('created_at', 'desc');
            },
            'category'
        ])->findOrFail($eventId);

        // Check if the authenticated user is the event owner
        if ($event->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized access to this event\'s booking requests.');
        }

        // Flatten all bookings from all booths
        $bookings = $event->booths->flatMap->bookings;

        // Calculate statistics
        $stats = [
            'total' => $bookings->count(),
            'pending' => $bookings->where('status', 'pending')->count(),
            'approved' => $bookings->where('status', 'confirmed')->count(),
            'rejected' => $bookings->where('status', 'rejected')->count(),
        ];

        return view('booking-requests.index', compact('event', 'bookings', 'stats'));
    }

    /**
     * Display a specific booking request details
     */
    public function bookingRequestDetails(Request $request, $eventId, $bookingId)
    {
        $event = \App\Models\Event::with('category')->findOrFail($eventId);

        // Check if the authenticated user is the event owner
        if ($event->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized access to this event\'s booking requests.');
        }

        $booking = Booking::with([
            'booth.event',
            'user',
            'payment'
        ])
            ->where('id', $bookingId)
            ->whereHas('booth', function ($query) use ($eventId) {
                $query->where('event_id', $eventId);
            })
            ->firstOrFail();

        return view('booking-requests.details', compact('event', 'booking'));
    }
}
