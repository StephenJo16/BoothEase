<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Booking Request</title>
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
            background-color: #e66600;
        }

        .notes-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin: 15px 0;
        }

        .notes-box p {
            margin: 0;
            line-height: 1.5;
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
            background-color: #ffc107;
            color: #000;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🎪 New Booking Request</h1>
        </div>

        <div class="alert-box">
            <strong>⚠️ Action Required</strong>
            <p>A tenant has submitted a booking request for your event. Please review the details below and take appropriate action.</p>
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

        <!-- Tenant Details -->
        <div class="info-section">
            <h2>Tenant Information</h2>
            <div class="info-row">
                <span class="info-label">Business Name:</span>
                <span class="info-value">{{ $tenant->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Contact Person:</span>
                <span class="info-value">{{ $tenant->display_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $tenant->email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Phone:</span>
                <span class="info-value">{{ $tenant->phone_number ?? 'Not provided' }}</span>
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
                <span class="info-value">{{ $booth->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Booth Type:</span>
                <span class="info-value">{{ ucfirst($booth->type) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Booth Size:</span>
                <span class="info-value">{{ $booth->size ?? 'Not specified' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status:</span>
                <span class="info-value"><span class="badge">{{ strtoupper($booking->status) }}</span></span>
            </div>
        </div>

        <!-- Booking Amount -->
        <div class="info-section">
            <h2>Booking Amount</h2>
            <div class="info-row">
                <span class="info-label">Total Amount:</span>
                <span class="info-value price">{{ formatRupiah($booking->total_price) }}</span>
            </div>
        </div>

        <!-- Action Button -->
        <table width="100%" cellpadding="0" cellspacing="0" style="margin: 30px 0;">
            <tr>
                <td align="center">
                    <a href="{{ route('booking-request-details', ['event' => $event->id, 'booking' => $booking->id]) }}"
                        style="display: inline-block; padding: 15px 30px; background-color: #ff7700; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold; text-align: center; font-family: Arial, sans-serif;">
                        Review Booking Request
                    </a>
                </td>
            </tr>
        </table>

        <!-- Additional Notes -->
        @if($booking->notes)
        <div class="info-section">
            <h2>Additional Notes from Tenant</h2>
            <div class="notes-box">
                <p>{{ $booking->notes }}</p>
            </div>
        </div>
        @endif

        <!-- Booking Date -->
        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Request Date:</span>
                <span class="info-value">{{ $booking->created_at->format('F d, Y \a\t h:i A') }}</span>
            </div>
        </div>

        <!-- Important Information -->
        <div class="info-section">
            <div style="background-color: #e7f3ff; border-left: 4px solid #2196F3; padding: 15px; border-radius: 4px;">
                <h3 style="color: #1976D2; margin: 0 0 10px 0; font-size: 16px;">📌 Next Steps</h3>
                <ul style="margin: 0; padding-left: 20px; color: #1565C0; font-size: 14px;">
                    <li>Review the booking details carefully</li>
                    <li>Approve or reject the booking request</li>
                    <li>The tenant will be notified of your decision via email</li>
                    <li>Once approved, the tenant must complete payment within 3 hours</li>
                </ul>
            </div>
        </div>

        <div class="footer">
            <p>This is an automated notification from BoothEase</p>
            <p>Please review this booking request at your earliest convenience</p>
        </div>
    </div>
</body>

</html>