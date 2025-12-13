<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Request Rejected</title>
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

        .error-box {
            background-color: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .error-box strong {
            color: #721c24;
            display: block;
            margin-bottom: 5px;
        }

        .error-box p {
            color: #721c24;
            margin: 0;
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
            background-color: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .reason-box h3 {
            color: #856404;
            margin: 0 0 10px 0;
            font-size: 18px;
        }

        .reason-box p {
            color: #856404;
            margin: 0;
            line-height: 1.6;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
            color: #666;
            font-size: 14px;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            background-color: #dc3545;
            color: #fff;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }

        .info-box {
            background-color: #d1ecf1;
            border-left: 4px solid #17a2b8;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .info-box p {
            color: #0c5460;
            margin: 0;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>❌ Refund Request Rejected</h1>
        </div>

        <div class="error-box">
            <strong>We're Sorry</strong>
            <p>Your refund request has been reviewed and rejected by the event organizer. Please see the reason below for more details.</p>
        </div>

        <!-- Rejection Reason -->
        <div class="reason-box">
            <h3>📝 Rejection Reason</h3>
            <p>{{ $refundRequest->rejection_reason }}</p>
        </div>

        <!-- Event Details -->
        <div class="info-section">
            <h2>Event Information</h2>
            <div class="info-row">
                <span class="info-label">Event Name:</span>
                <span class="info-value">{{ $event->title }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Venue:</span>
                <span class="info-value">{{ $event->venue ?? 'Not specified' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Date:</span>
                <span class="info-value">{{ formatEventDate($event) }}</span>
            </div>
        </div>

        <!-- Booking Details -->
        <div class="info-section">
            <h2>Booking Information</h2>
            <div class="info-row">
                <span class="info-label">Booking ID:</span>
                <span class="info-value">ID-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Booth:</span>
                <span class="info-value">{{ $booking->booth->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Booth Type:</span>
                <span class="info-value">{{ ucfirst($booking->booth->type) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Booking Status:</span>
                <span class="info-value"><span class="badge" style="background-color: #28a745;">ACTIVE</span></span>
            </div>
            <div class="info-row">
                <span class="info-label">Refund Status:</span>
                <span class="info-value"><span class="badge">REJECTED</span></span>
            </div>
        </div>

        <!-- Refund Request Details -->
        <div class="info-section">
            <h2>Refund Request Details</h2>
            <div class="info-row">
                <span class="info-label">Request Date:</span>
                <span class="info-value">{{ $refundRequest->created_at->format('F d, Y \a\t h:i A') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Rejection Date:</span>
                <span class="info-value">{{ $refundRequest->rejected_at->format('F d, Y \a\t h:i A') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Requested Amount:</span>
                <span class="info-value">{{ formatRupiah($refundRequest->refund_amount) }}</span>
            </div>
        </div>

        <!-- What This Means -->
        <div class="info-box">
            <p><strong>What does this mean?</strong><br>
                Your booking remains active and you are expected to attend the event as scheduled. The payment you made for this booth will not be refunded.</p>
        </div>

        <!-- Important Information -->
        <div class="info-section">
            <div style="background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 15px; border-radius: 4px;">
                <h3 style="color: #333; margin: 0 0 10px 0; font-size: 16px;">📌 Next Steps</h3>
                <ul style="margin: 0; padding-left: 20px; color: #666; font-size: 14px;">
                    <li>Your booking is still confirmed and active</li>
                    <li>Please prepare for the event as scheduled</li>
                    <li>If you have questions about the rejection, contact the event organizer</li>
                    <li>If you believe this decision was made in error, you may contact our support team</li>
                </ul>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="info-section">
            <div style="background-color: #e7f3ff; border-left: 4px solid #2196F3; padding: 15px; border-radius: 4px;">
                <h3 style="color: #1976D2; margin: 0 0 10px 0; font-size: 16px;">💬 Need Help?</h3>
                <p style="color: #1565C0; margin: 0; font-size: 14px;">
                    If you have any questions or concerns about this rejection, please don't hesitate to contact our support team or reach out to the event organizer directly.
                </p>
            </div>
        </div>

        <div class="footer">
            <p>Thank you for using BoothEase</p>
            <p>We appreciate your understanding</p>
        </div>
    </div>
</body>

</html>