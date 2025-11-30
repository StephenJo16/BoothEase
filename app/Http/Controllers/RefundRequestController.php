<?php

namespace App\Http\Controllers;

use App\Models\RefundRequest;
use App\Models\Event;
use App\Http\Requests\StoreRefundRequestRequest;
use App\Http\Requests\UpdateRefundRequestRequest;
use Illuminate\Http\Request;

class RefundRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Event $event)
    {
        // Verify the event belongs to the current user
        if ($event->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized access to this event.');
        }

        // Get filter parameters
        $search = $request->input('search');
        $statuses = $request->input('statuses', []);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Build query for refund requests with relationships
        // Only show refund requests for this specific event
        $query = RefundRequest::with([
            'user',
            'booking.booth.event',
            'booking.booth',
            'booking.payment'
        ])
            ->whereHas('booking.booth.event', function ($q) use ($event) {
                $q->where('id', $event->id);
            });

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('reason', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('booking', function ($q) use ($search) {
                        $q->where('id', 'like', '%' . $search . '%');
                    });
            });
        }

        // Status filter
        if (!empty($statuses)) {
            $query->whereIn('status', $statuses);
        }

        // Date range filter
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        // Get refund requests ordered by latest first
        $perPage = request('perPage', 5);
        $refundRequests = $query->latest()->paginate($perPage)->withQueryString();

        // Calculate statistics for this event
        $totalRequests = RefundRequest::whereHas('booking.booth.event', function ($q) use ($event) {
            $q->where('id', $event->id);
        })->count();

        $pendingCount = RefundRequest::whereHas('booking.booth.event', function ($q) use ($event) {
            $q->where('id', $event->id);
        })->where('status', RefundRequest::STATUS_PENDING)->count();

        $approvedCount = RefundRequest::whereHas('booking.booth.event', function ($q) use ($event) {
            $q->where('id', $event->id);
        })->where('status', RefundRequest::STATUS_APPROVED)->count();

        $rejectedCount = RefundRequest::whereHas('booking.booth.event', function ($q) use ($event) {
            $q->where('id', $event->id);
        })->where('status', RefundRequest::STATUS_REJECTED)->count();

        return view('refund-requests.index', [
            'event' => $event,
            'refundRequests' => $refundRequests,
            'totalRequests' => $totalRequests,
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'rejectedCount' => $rejectedCount,
            'filters' => [
                'search' => $search,
                'statuses' => $statuses,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $bookingId)
    {
        $booking = \App\Models\Booking::with(['booth.event', 'payment', 'user'])
            ->findOrFail($bookingId);

        // Verify booking belongs to authenticated user
        if ($booking->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized access to this booking.');
        }

        // Check if refund can be requested
        if (!RefundRequest::canRequestRefund($booking)) {
            return redirect()->route('my-booking-details', $booking->id)
                ->with('error', 'Refund cannot be requested for this booking. Either the event does not allow refunds, the booking is not paid, or a refund request already exists.');
        }

        $event = $booking->booth->event;

        // Calculate refund amount (as integers, no decimals)
        $processingFeePercentage = 30; // 30%
        $processingFee = intval(($booking->total_price * $processingFeePercentage) / 100);
        $refundAmount = $booking->total_price - $processingFee;

        return view('request-refund.index', [
            'booking' => $booking,
            'event' => $event,
            'processingFee' => $processingFee,
            'refundAmount' => $refundAmount,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $bookingId)
    {
        $booking = \App\Models\Booking::with(['booth.event'])
            ->findOrFail($bookingId);

        // Verify booking belongs to authenticated user
        if ($booking->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized access to this booking.');
        }

        // Check if refund can be requested
        if (!RefundRequest::canRequestRefund($booking)) {
            return redirect()->route('my-booking-details', $booking->id)
                ->with('error', 'Refund cannot be requested for this booking.');
        }

        // Validate the request
        $validated = $request->validate([
            'account_holder_name' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:50'],
            'reason' => ['required', 'string', 'min:10'],
            'document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'], // 2MB max
        ]);

        // Handle file upload if present
        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('refund-documents', 'public');
        }

        // Calculate refund amount (as integers, no decimals)
        $processingFeePercentage = 30; // 30%
        $processingFee = intval(($booking->total_price * $processingFeePercentage) / 100);
        $refundAmount = $booking->total_price - $processingFee;

        // Create the refund request
        $refundRequest = RefundRequest::create([
            'user_id' => $request->user()->id,
            'booking_id' => $booking->id,
            'reason' => $validated['reason'],
            'account_holder_name' => $validated['account_holder_name'],
            'bank_name' => $validated['bank_name'],
            'account_number' => $validated['account_number'],
            'document' => $documentPath,
            'refund_amount' => $refundAmount,
            'processing_fee' => $processingFee,
            'status' => RefundRequest::STATUS_PENDING,
        ]);

        return redirect()->route('my-booking-details', $booking->id)
            ->with('success', 'Refund request submitted successfully! You will receive an email notification regarding the status of your request.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Event $event, RefundRequest $refundRequest)
    {
        // Load relationships
        $refundRequest->load([
            'user',
            'booking.booth.event',
            'booking.payment'
        ]);

        // Verify the refund request belongs to the specified event
        if ($refundRequest->booking->booth->event->id !== $event->id) {
            abort(403, 'This refund request does not belong to this event.');
        }

        // Verify the event belongs to the current user
        if ($event->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized access to this event.');
        }

        $booking = $refundRequest->booking;
        $event = $booking->booth->event;

        // Format event dates
        $dateDisplay = 'Schedule to be announced';
        if ($event->start_time && $event->end_time) {
            $startDate = $event->start_time->format('d M Y');
            $endDate = $event->end_time->format('d M Y');
            $dateDisplay = $event->start_time->isSameDay($event->end_time) ? $startDate : "{$startDate} - {$endDate}";
        } elseif ($event->start_time) {
            $dateDisplay = $event->start_time->format('d M Y');
        } elseif ($event->end_time) {
            $dateDisplay = $event->end_time->format('d M Y');
        }

        return view('refund-requests.details', [
            'refundRequest' => $refundRequest,
            'booking' => $booking,
            'event' => $event,
            'dateDisplay' => $dateDisplay,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RefundRequest $refundRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRefundRequestRequest $request, RefundRequest $refundRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RefundRequest $refundRequest)
    {
        //
    }

    /**
     * Approve a refund request.
     */
    public function approve(Request $request, Event $event, RefundRequest $refundRequest)
    {
        // Load relationships
        $refundRequest->load('booking.booth.event');

        // Verify the refund request belongs to the specified event
        if ($refundRequest->booking->booth->event->id !== $event->id) {
            abort(403, 'This refund request does not belong to this event.');
        }

        // Verify the event belongs to the current user
        if ($event->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized access to this event.');
        }

        // Update refund request status to approved
        $refundRequest->update([
            'status' => RefundRequest::STATUS_APPROVED,
        ]);

        // Update booking status to cancelled
        $refundRequest->booking->update([
            'status' => \App\Models\Booking::STATUS_CANCELLED,
        ]);

        // Change booth status back to available
        $refundRequest->booking->booth->update([
            'status' => 'available',
        ]);

        return redirect()->route('refund-requests.show', ['event' => $event->id, 'refundRequest' => $refundRequest->id])
            ->with('success', 'Refund request has been approved successfully! The booking has been cancelled and the booth is now available.');
    }

    /**
     * Reject a refund request.
     */
    public function reject(Request $request, Event $event, RefundRequest $refundRequest)
    {
        // Load relationships
        $refundRequest->load('booking.booth.event');

        // Verify the refund request belongs to the specified event
        if ($refundRequest->booking->booth->event->id !== $event->id) {
            abort(403, 'This refund request does not belong to this event.');
        }

        // Verify the event belongs to the current user
        if ($event->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized access to this event.');
        }

        // Validate the rejection reason
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        // Update status to rejected with reason
        $refundRequest->update([
            'status' => RefundRequest::STATUS_REJECTED,
            'rejection_reason' => $validated['rejection_reason'],
            'rejected_at' => now(),
        ]);

        return redirect()->route('refund-requests.show', ['event' => $event->id, 'refundRequest' => $refundRequest->id])
            ->with('success', 'Refund request has been rejected.');
    }
}
