<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Mail\BookingConfirmedMail;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Update booking statuses before loading
        $this->updateBookingStatuses();

        $userId = Auth::id();

        // Get filter parameters
        $search = $request->input('search');
        $statuses = $request->input('statuses', []);
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');

        // Build query with filters
        $query = Booking::with(['booth.event.category', 'user', 'refundRequest'])
            ->where('user_id', $userId);

        // Search filter - search in event title, venue, booth name, booking ID
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('booth.event', function ($eventQuery) use ($search) {
                    $eventQuery->where('title', 'like', '%' . $search . '%')
                        ->orWhereJsonContains('location->venue', $search)
                        ->orWhereJsonContains('location->city', $search);
                })
                    ->orWhereHas('booth', function ($boothQuery) use ($search) {
                        $boothQuery->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhere('id', 'like', '%' . $search . '%');
            });
        }

        // Status filter
        if (!empty($statuses)) {
            $query->whereIn('status', $statuses);
        }

        // Price filter
        if ($minPrice !== null) {
            $query->where('total_price', '>=', $minPrice);
        }
        if ($maxPrice !== null) {
            $query->where('total_price', '<=', $maxPrice);
        }

        // Get per page value from request, default to 5
        $perPage = request('perPage', 5);
        $bookings = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();

        // Calculate statistics (all bookings, not filtered)
        $allBookings = Booking::where('user_id', $userId)->get();
        $totalBookings = $allBookings->count();
        $confirmedBookings = $allBookings->where('status', 'confirmed')->count();
        $completedBookings = $allBookings->where('status', 'completed')->count();

        // Compute total spent across the user's paid/completed bookings.
        $totalSpent = Booking::where('user_id', $userId)
            ->whereIn('status', ['paid', 'completed'])
            ->sum('total_price');

        return view('my-bookings.index', [
            'bookings' => $bookings,
            'totalBookings' => $totalBookings,
            'confirmedBookings' => $confirmedBookings,
            'completedBookings' => $completedBookings,
            'totalSpent' => $totalSpent,
            'filters' => [
                'search' => $search,
                'statuses' => $statuses,
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
            ],
        ]);
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
            $fullName = $validated['full_name'];
            $user = \App\Models\User::where('email', $validated['email'])->first();

            if (!$user) {
                // Get default role (user role) - usually role_id = 2 for regular users
                $defaultRole = \App\Models\Role::where('name', 'user')->first();
                if (!$defaultRole) {
                    $defaultRole = \App\Models\Role::first(); // Fallback to first role if 'user' role doesn't exist
                }

                // Get a default category (first one available)
                $defaultCategory = \App\Models\Category::first();

                $user = \App\Models\User::create([
                    'role_id' => $defaultRole->id,
                    'category_id' => $defaultCategory ? $defaultCategory->id : null,
                    'name' => $validated['business_name'],
                    'display_name' => $fullName,
                    'email' => $validated['email'],
                    'phone_number' => '+62' . $validated['phone'],
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
            'payment',
            'refundRequest'
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
                        $boothQuery->where('name', 'like', "%{$search}%");
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

        // Get per page value from request, default to 5
        $perPage = $request->integer('perPage', 5);

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
            'user.ratingsReceived.event',
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
        // Validate rejection reason
        $request->validate([
            'rejection_reason' => 'required|string|min:10|max:1000',
        ]);

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

        DB::transaction(function () use ($booking, $targetStatus, $request) {
            $updateData = ['status' => $targetStatus];

            // Set confirmed_at timestamp when confirming a booking
            if ($targetStatus === 'confirmed') {
                $updateData['confirmed_at'] = now();
            }

            // Set rejection_reason and rejected_at when rejecting
            if ($targetStatus === 'rejected') {
                $updateData['rejection_reason'] = $request->input('rejection_reason');
                $updateData['rejected_at'] = now();
            }

            $booking->update($updateData);

            if ($targetStatus === 'rejected') {
                $booking->booth?->update(['status' => 'available']);
            } elseif ($targetStatus === 'confirmed') {
                // Keep booth as 'pending' until payment is completed
                $booking->booth?->update(['status' => 'pending']);

                // Send email notification to tenant
                try {
                    // Load relationships needed for the email
                    $booking->load(['user', 'booth.event.user']);
                    Mail::to($booking->user->email)->send(new BookingConfirmedMail($booking));
                } catch (\Exception $e) {
                    Log::error('Failed to send booking confirmation email: ' . $e->getMessage());
                    // Don't fail the transaction, just log the error
                }
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

        // Cancel unpaid bookings that have been confirmed for more than 3 hours
        Booking::where('status', 'confirmed')
            ->whereNotNull('confirmed_at')
            ->where('confirmed_at', '<=', $now->copy()->subHours(3))
            ->whereDoesntHave('payment', function ($query) {
                $query->where('payment_status', 'completed');
            })
            ->update(['status' => 'cancelled']);

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

    /**
     * Show attendant details for event organizer
     */
    public function showAttendant(Request $request, $eventId, $bookingId)
    {
        $booking = Booking::with([
            'booth.event.category',
            'user.ratingsReceived.event',
            'payment'
        ])->findOrFail($bookingId);

        $event = $booking->booth->event;

        // Validate that the event belongs to the authenticated user (organizer)
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this attendant.');
        }

        // Validate that the booking belongs to the specified event
        if ($booking->booth->event_id != $eventId) {
            abort(404, 'Booking not found for this event.');
        }

        return view('attendants.details', compact('booking', 'event'));
    }
}
