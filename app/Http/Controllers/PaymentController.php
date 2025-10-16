<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Booking;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Booking $booking)
    {
        // Check if booking belongs to authenticated user
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to booking');
        }

        // Check if booking status is confirmed
        if ($booking->status !== 'confirmed') {
            return redirect()->route('my-booking-details', $booking->id)
                ->with('error', 'Only confirmed bookings can proceed to payment');
        }

        // Check if payment already exists and is completed
        if ($booking->payment && $booking->payment->payment_status === 'completed') {
            return redirect()->route('my-booking-details', $booking->id)
                ->with('info', 'Payment has already been completed');
        }

        return view('payments.create', compact('booking'));
    }

    /**
     * Initialize payment with Midtrans Snap
     */
    public function initiate(Request $request, Booking $booking)
    {
        // Check if booking belongs to authenticated user
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to booking');
        }

        try {
            // Set Midtrans configuration
            $serverKey = config('services.midtrans.server_key');
            $isProduction = config('services.midtrans.is_production', false);
            $snapUrl = $isProduction
                ? 'https://app.midtrans.com/snap/v1/transactions'
                : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

            // Create or update payment record
            $payment = Payment::updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'payment_method' => 'midtrans',
                    'payment_status' => 'pending',
                    'amount' => $booking->total_price,
                ]
            );

            // Prepare transaction details for Midtrans
            $orderId = 'BOOKING-' . $booking->id . '-' . time();
            $transactionDetails = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $booking->total_price,
                ],
                'customer_details' => [
                    'first_name' => $booking->user->name,
                    'email' => $booking->user->email,
                    'phone' => $booking->user->phone_number ?? '',
                ],
                'item_details' => [
                    [
                        'id' => 'booth-' . $booking->booth->id,
                        'price' => (int) $booking->total_price,
                        'quantity' => 1,
                        'name' => 'Booth ' . $booking->booth->number . ' - ' . $booking->booth->event->title,
                    ],
                ],
            ];

            // Call Midtrans Snap API
            $response = Http::withBasicAuth($serverKey, '')
                ->post($snapUrl, $transactionDetails);

            if ($response->successful()) {
                $snapToken = $response->json('token');

                // Save snap token to payment
                $payment->update([
                    'snap_token' => $snapToken,
                    'transaction_id' => $orderId,
                ]);

                return response()->json([
                    'success' => true,
                    'snap_token' => $snapToken,
                    'client_key' => config('services.midtrans.client_key'),
                ]);
            } else {
                Log::error('Midtrans Snap API Error: ' . $response->body());
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to initialize payment gateway'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Payment initialization error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while initializing payment'
            ], 500);
        }
    }

    /**
     * Handle payment callback/notification from Midtrans
     */
    public function callback(Request $request)
    {
        try {
            $serverKey = config('services.midtrans.server_key');
            $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

            // Verify signature
            if ($hashed !== $request->signature_key) {
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            // Find payment by transaction ID
            $payment = Payment::where('transaction_id', $request->order_id)->first();

            if (!$payment) {
                return response()->json(['message' => 'Payment not found'], 404);
            }

            // Update payment status based on Midtrans transaction status
            $transactionStatus = $request->transaction_status;
            $fraudStatus = $request->fraud_status ?? 'accept';

            // Capture payment type and channel from Midtrans
            $payment->payment_type = $request->payment_type;

            // For bank transfers, capture the specific bank
            if ($request->payment_type === 'bank_transfer' && isset($request->va_numbers[0]['bank'])) {
                $payment->payment_channel = $request->va_numbers[0]['bank'];
            } elseif ($request->payment_type === 'echannel') {
                $payment->payment_channel = 'mandiri';
            } elseif ($request->payment_type === 'permata') {
                $payment->payment_channel = 'permata';
            }

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $payment->payment_status = 'completed';
                    $payment->payment_date = now();
                }
            } else if ($transactionStatus == 'settlement') {
                $payment->payment_status = 'completed';
                $payment->payment_date = now();
            } else if ($transactionStatus == 'pending') {
                $payment->payment_status = 'pending';
            } else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $payment->payment_status = 'failed';
            }

            $payment->save();

            // Update booking status if payment is completed
            if ($payment->payment_status === 'completed') {
                $payment->booking->update(['status' => 'paid']);
            }

            return response()->json(['message' => 'Callback processed']);
        } catch (\Exception $e) {
            Log::error('Payment callback error: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing callback'], 500);
        }
    }

    /**
     * Handle payment success (user redirect)
     */
    public function success(Booking $booking)
    {
        // Update payment status when user is redirected after successful payment
        // This is a fallback in case the webhook doesn't arrive immediately
        if ($booking->payment && $booking->payment->payment_status !== 'completed') {
            // Verify payment status with Midtrans API
            $this->verifyPaymentStatus($booking->payment);
        }

        return redirect()->route('my-booking-details', $booking->id)
            ->with('success', 'Payment completed successfully!');
    }

    /**
     * Handle payment pending (user redirect)
     */
    public function pending(Booking $booking)
    {
        // Check payment status
        if ($booking->payment) {
            $this->verifyPaymentStatus($booking->payment);
        }

        return redirect()->route('my-booking-details', $booking->id)
            ->with('info', 'Payment is being processed. We will notify you once completed.');
    }

    /**
     * Verify payment status with Midtrans API
     */
    private function verifyPaymentStatus(Payment $payment)
    {
        try {
            $serverKey = config('services.midtrans.server_key');
            $isProduction = config('services.midtrans.is_production', false);
            $statusUrl = $isProduction
                ? "https://api.midtrans.com/v2/{$payment->transaction_id}/status"
                : "https://api.sandbox.midtrans.com/v2/{$payment->transaction_id}/status";

            $response = Http::withBasicAuth($serverKey, '')
                ->get($statusUrl);

            if ($response->successful()) {
                $result = $response->json();
                $transactionStatus = $result['transaction_status'] ?? 'pending';
                $fraudStatus = $result['fraud_status'] ?? 'accept';

                // Capture payment type and channel
                if (isset($result['payment_type'])) {
                    $payment->payment_type = $result['payment_type'];
                }

                // For bank transfers, capture the specific bank
                if (isset($result['payment_type']) && $result['payment_type'] === 'bank_transfer' && isset($result['va_numbers'][0]['bank'])) {
                    $payment->payment_channel = $result['va_numbers'][0]['bank'];
                } elseif (isset($result['payment_type']) && $result['payment_type'] === 'echannel') {
                    $payment->payment_channel = 'mandiri';
                } elseif (isset($result['permata_va_number'])) {
                    $payment->payment_channel = 'permata';
                }

                // Update payment status based on Midtrans response
                if ($transactionStatus == 'capture') {
                    if ($fraudStatus == 'accept') {
                        $payment->payment_status = 'completed';
                        $payment->payment_date = now();
                        $payment->save();

                        // Update booking status
                        $payment->booking->update(['status' => 'paid']);
                    }
                } else if ($transactionStatus == 'settlement') {
                    $payment->payment_status = 'completed';
                    $payment->payment_date = now();
                    $payment->save();

                    // Update booking status
                    $payment->booking->update(['status' => 'paid']);
                } else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                    $payment->payment_status = 'failed';
                    $payment->save();
                }
            }
        } catch (\Exception $e) {
            Log::error('Payment verification error: ' . $e->getMessage());
        }
    }

    /**
     * Handle payment error (user redirect)
     */
    public function error(Booking $booking)
    {
        return redirect()->route('my-booking-details', $booking->id)
            ->with('error', 'Payment failed. Please try again.');
    }

    /**
     * Manually check payment status (AJAX endpoint)
     */
    public function checkStatus(Booking $booking)
    {
        // Check if booking belongs to authenticated user
        if ($booking->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if (!$booking->payment) {
            return response()->json(['success' => false, 'message' => 'No payment found'], 404);
        }

        $this->verifyPaymentStatus($booking->payment);

        // Refresh payment data
        $booking->payment->refresh();
        $booking->refresh();

        return response()->json([
            'success' => true,
            'payment_status' => $booking->payment->payment_status,
            'booking_status' => $booking->status,
            'message' => $booking->payment->payment_status === 'completed'
                ? 'Payment verified successfully!'
                : 'Payment is still ' . $booking->payment->payment_status
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
