<?php

namespace App\Http\Controllers;

use App\Models\RefundRequest;
use App\Http\Requests\StoreRefundRequestRequest;
use App\Http\Requests\UpdateRefundRequestRequest;
use Illuminate\Http\Request;

class RefundRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $search = $request->input('search');
        $statuses = $request->input('statuses', []);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Build query for refund requests with relationships
        // Only show refund requests for events owned by the current user
        $query = RefundRequest::with([
            'user',
            'booking.booth.event' => function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            },
            'booking.booth',
            'booking.payment'
        ])
            ->whereHas('booking.booth.event', function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
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
        $refundRequests = $query->latest()->paginate(10);

        // Calculate statistics
        $totalRequests = RefundRequest::whereHas('booking.booth.event', function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
        })->count();

        $pendingCount = RefundRequest::whereHas('booking.booth.event', function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
        })->where('status', RefundRequest::STATUS_PENDING)->count();

        $approvedCount = RefundRequest::whereHas('booking.booth.event', function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
        })->where('status', RefundRequest::STATUS_APPROVED)->count();

        $rejectedCount = RefundRequest::whereHas('booking.booth.event', function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
        })->where('status', RefundRequest::STATUS_REJECTED)->count();

        return view('refund-requests.index', [
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
    public function show(RefundRequest $refundRequest)
    {
        //
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
}
