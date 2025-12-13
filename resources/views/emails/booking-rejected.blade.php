<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Request Rejected</title>
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
            border-bottom: 3px solid #dc3545;
        }

        .header h1 {
            color: #dc3545;
            margin: 0;
            font-size: 28px;
        }

        .alert-box {
            background-color: #ffe6e6;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .alert-box strong {
            color: #dc3545;
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

        .reason-box {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #dc3545;
            margin: 20px 0;
        }

        .reason-box strong {
            color: #dc3545;
            display: block;
            margin-bottom: 10px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            color: #666;
            font-size: 14px;
        }

        .suggestion-box {
            background-color: #e8f4f8;
            border-left: 4px solid #17a2b8;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .suggestion-box strong {
            color: #17a2b8;
            display: block;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>❌ Booking Request Rejected</h1>
        </div>

        <p>Dear {{ $booking->user->display_name ?? $booking->user->name }},</p>

        <p>We regret to inform you that your booking request has been <strong>rejected</strong> by the event organizer.</p>

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
                <span class="info-label">Requested Amount:</span>
                <span class="info-value">{{ formatRupiah($booking->total_price) }}</span>
            </div>
            @if($booking->rejected_at)
            <div class="info-row">
                <span class="info-label">Rejected On:</span>
                <span class="info-value">{{ $booking->rejected_at->format('F d, Y - H:i') }}</span>
            </div>
            @endif
        </div>

        @if($booking->rejection_reason)
        <div class="reason-box">
            <strong>📝 Reason for Rejection:</strong>
            <p style="margin: 0; color: #333;">{{ $booking->rejection_reason }}</p>
        </div>
        @endif

        <div class="suggestion-box">
            <strong>💡 What You Can Do Next:</strong>
            <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                <li>Review the rejection reason provided by the organizer</li>
                <li>Browse other available events and booths on BoothEase</li>
                <li>Contact the event organizer directly if you have questions</li>
                <li>Consider submitting a request for a different booth at this event</li>
            </ul>
        </div>

        <div class="footer">
            <p>If you have any questions or concerns, please contact the event organizer directly.</p>
            <p style="margin-top: 10px;">
                <strong>Event Organizer:</strong> {{ $booking->booth->event->user->name ?? 'N/A' }}<br>
                <strong>Email:</strong> {{ $booking->booth->event->user->email ?? 'N/A' }}
            </p>
            <p style="margin-top: 20px;">
                We appreciate your interest in this event and hope to see you at future opportunities on BoothEase.
            </p>
            <p style="margin-top: 20px; font-size: 12px; color: #999;">
                This is an automated email from BoothEase. Please do not reply to this email.
            </p>
        </div>
    </div>
</body>

</html>