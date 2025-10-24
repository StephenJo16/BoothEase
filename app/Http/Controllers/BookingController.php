<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Update booking statuses before loading
        $this->updateBookingStatuses();

        // Get all bookings with related booth and event data
        $bookings = Booking::with(['booth.event.category', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $totalBookings = $bookings->count();
        $confirmedBookings = $bookings->where('status', 'confirmed')->count();
        $totalSpent = $bookings->where('status', 'confirmed')->sum('total_price');

        // Group bookings by status for filtering
        $bookingsByStatus = [
            'all' => $bookings,
            'confirmed' => $bookings->where('status', 'confirmed'),
            'rejected' => $bookings->where('status', 'rejected'),
            'cancelled' => $bookings->where('status', 'cancelled'),
        ];

        return view('my-bookings.index', compact(
            'bookings',
            'totalBookings',
            'confirmedBookings',
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

            // Update booth status to pending (will become 'booked' after payment)
            $booth->update(['status' => 'pending']);

            return redirect()->route('my-bookings')
                ->with('success', 'Booking request submitted successfully! Your booking is pending confirmation.');
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
        // Update booking statuses before loading
        $this->updateBookingStatuses();

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
        // Get the event
        $event = \App\Models\Event::with('category')->findOrFail($eventId);

        // Check if the authenticated user is the event owner
        if ($event->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized access to this event\'s booking requests.');
        }

        // Get all booth IDs for this event
        $boothIds = $event->booths()->pluck('id');

        // Build query for bookings
        $query = \App\Models\Booking::whereIn('booth_id', $boothIds)
            ->with(['user', 'booth']);

        // Apply filters if provided
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('display_name', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%");
                })
                    ->orWhereHas('booth', function ($boothQuery) use ($search) {
                        $boothQuery->where('number', 'like', "%{$search}%");
                    })
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }

        // Order by created_at descending
        $query->orderBy('created_at', 'desc');

        // Get per page value from request, default to 10
        $perPage = $request->integer('perPage', 10);

        // Paginate results
        $bookings = $query->paginate($perPage)->withQueryString();

        // Calculate statistics (all bookings, not just current page)
        $allBookings = \App\Models\Booking::whereIn('booth_id', $boothIds)->get();
        $stats = [
            'total' => $allBookings->count(),
            'pending' => $allBookings->where('status', 'pending')->count(),
            'confirmed' => $allBookings->where('status', 'confirmed')->count(),
            'rejected' => $allBookings->where('status', 'rejected')->count(),
            'paid' => $allBookings->where('status', 'paid')->count(),
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

    public function confirmBookingRequest(Request $request, $eventId, $bookingId)
    {
        return $this->changeBookingRequestStatus($request, $eventId, $bookingId, 'confirmed');
    }

    public function rejectBookingRequest(Request $request, $eventId, $bookingId)
    {
        return $this->changeBookingRequestStatus($request, $eventId, $bookingId, 'rejected');
    }

    private function changeBookingRequestStatus(Request $request, $eventId, $bookingId, string $targetStatus)
    {
        [$event, $booking] = $this->resolveAuthorizedBooking($request, $eventId, $bookingId);

        if ($booking->status === $targetStatus) {
            return redirect()
                ->route('booking-request-details', ['event' => $eventId, 'booking' => $bookingId])
                ->with('info', "Booking request is already {$targetStatus}.");
        }

        if ($booking->status !== 'pending') {
            return redirect()
                ->route('booking-request-details', ['event' => $eventId, 'booking' => $bookingId])
                ->with('error', 'Only pending booking requests can be updated.');
        }

        DB::transaction(function () use ($booking, $targetStatus) {
            $booking->update(['status' => $targetStatus]);

            if ($targetStatus === 'rejected') {
                $booking->booth?->update(['status' => 'available']);
            } elseif ($targetStatus === 'confirmed') {
                // Keep booth as 'pending' until payment is completed
                $booking->booth?->update(['status' => 'pending']);
            }
        });

        $message = $targetStatus === 'confirmed'
            ? 'Booking request confirmed successfully.'
            : 'Booking request rejected successfully.';

        return redirect()
            ->route('booking-request-details', ['event' => $eventId, 'booking' => $bookingId])
            ->with('success', $message);
    }

    private function resolveAuthorizedBooking(Request $request, $eventId, $bookingId): array
    {
        $event = \App\Models\Event::findOrFail($eventId);

        if ($event->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized access to this event\'s booking requests.');
        }

        $booking = Booking::with('booth')
            ->where('id', $bookingId)
            ->whereHas('booth', function ($query) use ($eventId) {
                $query->where('event_id', $eventId);
            })
            ->firstOrFail();

        return [$event, $booking];
    }

    /**
     * Update booking statuses based on associated event date and time
     */
    private function updateBookingStatuses(): void
    {
        $now = now();

        // Update bookings to 'ongoing' status when event has started
        Booking::whereHas('booth.event', function ($query) use ($now) {
            $query->where('start_time', '<=', $now)
                ->where('end_time', '>=', $now);
        })
            ->whereIn('status', ['pending', 'confirmed', 'paid'])
            ->update(['status' => 'ongoing']);

        // Update bookings to 'completed' status when event has ended
        Booking::whereHas('booth.event', function ($query) use ($now) {
            $query->where('end_time', '<', $now);
        })
            ->whereIn('status', ['pending', 'confirmed', 'paid', 'ongoing'])
            ->update(['status' => 'completed']);
    }

    /**
     * Download invoice for a booking
     */
    public function downloadInvoice(Request $request, Booking $booking)
    {
        // Load relationships
        $booking->load([
            'booth.event.category',
            'booth.event.user',
            'user',
            'payment'
        ]);

        // Check if user is authorized to download this invoice
        if ($request->user()->id !== $booking->user_id) {
            abort(403, 'Unauthorized access to this invoice.');
        }

        // Check if booking has payment completed
        if (!$booking->payment || $booking->payment->payment_status !== 'completed') {
            return redirect()->back()->with('error', 'Invoice is only available for completed payments.');
        }

        $event = $booking->booth->event;
        $booth = $booking->booth;

        // Prepare data for invoice
        $data = [
            'booking' => $booking,
            'event' => $event,
            'booth' => $booth,
            'user' => $booking->user,
            'payment' => $booking->payment,
            'invoiceNumber' => 'INV-' . str_pad($booking->id, 6, '0', STR_PAD_LEFT),
            'invoiceDate' => $booking->payment->updated_at ?? $booking->payment->created_at ?? now(),
        ];

        // Generate PDF with options to support PNG images
        $pdf = PDF::setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
        ])->loadView('invoices.booking', $data);

        // Download the PDF
        $filename = 'invoice-' . str_pad($booking->id, 6, '0', STR_PAD_LEFT) . '.pdf';
        return $pdf->download($filename);
    }
}
