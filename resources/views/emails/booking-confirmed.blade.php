<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #ff7700;
        }

        .header h1 {
            color: #ff7700;
            margin: 0;
            font-size: 28px;
        }

        .alert-box {
            background-color: #fff3cd;
            border-left: 4px solid #ff7700;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .alert-box strong {
            color: #ff7700;
            display: block;
            margin-bottom: 5px;
        }

        .info-section {
            margin: 25px 0;
        }

        .info-section h2 {
            color: #333;
            font-size: 18px;
            margin-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 10px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: bold;
            color: #666;
        }

        .info-value {
            color: #333;
        }

        .price {
            font-size: 24px;
            font-weight: bold;
            color: #ff7700;
        }

        .button {
            display: inline-block;
            padding: 15px 30px;
            background-color: #ff7700;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
        }

        .button:hover {
            background-color: #e66900;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            color: #666;
            font-size: 14px;
        }

        .deadline {
            background-color: #fef5e7;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            margin: 20px 0;
        }

        .deadline-time {
            font-size: 20px;
            font-weight: bold;
            color: #ff7700;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Booking Confirmed!</h1>
        </div>

        <p>Dear {{ $booking->user->display_name ?? $booking->user->name }},</p>

        <p>Great news! Your booking request has been <strong>confirmed</strong> by the event organizer.</p>

        <div class="alert-box">
            <strong>⏰ Action Required: Complete Payment Within 3 Hours</strong>
            <p style="margin: 5px 0 0 0;">To secure your booth, please complete the payment before the deadline below. Your booking will be automatically cancelled if payment is not received within 3 hours.</p>
        </div>

        <div class="deadline">
            <div>Payment Deadline:</div>
            <div class="deadline-time">{{ $paymentDeadline->format('F d, Y - H:i') }}</div>
        </div>

        <div class="info-section">
            <h2>Booking Details</h2>
            <div class="info-row">
                <span class="info-label">Booking ID:</span>
                <span class="info-value">REQ{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Event:</span>
                <span class="info-value">{{ $booking->booth->event->title ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Booth:</span>
                <span class="info-value">{{ $booking->booth->name ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Booth Type:</span>
                <span class="info-value">{{ ucfirst($booking->booth->type ?? 'N/A') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Event Date:</span>
                <span class="info-value">{{ $booking->booth->event->start_time->format('F d, Y') ?? 'N/A' }}</span>
            </div>
        </div>

        <div class="info-section">
            <h2>Payment Information</h2>
            <div class="info-row">
                <span class="info-label">Total Amount:</span>
                <span class="price">{{ formatRupiah($booking->total_price) }}</span>
            </div>
        </div>

        <div style="text-align: center;">
            <a href="{{ route('payment.create', ['booking' => $booking->id]) }}" class="button">
                Complete Payment Now
            </a>
        </div>

        <div class="info-section">
            <h2>What's Next?</h2>
            <ol style="padding-left: 20px;">
                <li>Click the button above to proceed with payment</li>
                <li>Complete the payment before {{ $paymentDeadline->format('F d, Y - H:i') }}</li>
                <li>You'll receive a confirmation email once payment is successful</li>
                <li>Your invoice will be available in your booking details</li>
            </ol>
        </div>

        @if($booking->notes)
        <div class="info-section">
            <h2>Special Requests / Notes</h2>
            <p style="background-color: #f9f9f9; padding: 15px; border-radius: 5px; font-style: italic;">
                {{ $booking->notes }}
            </p>
        </div>
        @endif

        <div class="alert-box" style="background-color: #ffe6e6; border-left-color: #dc3545;">
            <strong>⚠️ Important Notice</strong>
            <p style="margin: 5px 0 0 0;">If payment is not completed within 3 hours, your booking will be automatically cancelled and the booth will become available for other tenants.</p>
        </div>

        <div class="footer">
            <p>If you have any questions or need assistance, please contact the event organizer.</p>
            <p style="margin-top: 10px;">
                <strong>Event Organizer:</strong> {{ $booking->booth->event->user->name ?? 'N/A' }}<br>
                <strong>Email:</strong> {{ $booking->booth->event->user->email ?? 'N/A' }}
            </p>
            <p style="margin-top: 20px; font-size: 12px; color: #999;">
                This is an automated email from BoothEase. Please do not reply to this email.
            </p>
        </div>
    </div>
</body>

</html>