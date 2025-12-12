<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Refund Request</title>
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

        .reason-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin: 15px 0;
        }

        .reason-box p {
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
            <h1>📋 New Refund Request</h1>
        </div>

        <div class="alert-box">
            <strong>⚠️ Action Required</strong>
            <p>A tenant has submitted a refund request for your event. Please review the details below and take appropriate action.</p>
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
                <span class="info-label">Name:</span>
                <span class="info-value">{{ $tenant->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $tenant->email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Phone:</span>
                <span class="info-value">{{ $tenant->phone ?? 'Not provided' }}</span>
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
                <span class="info-label">Status:</span>
                <span class="info-value"><span class="badge">{{ strtoupper($refundRequest->status) }}</span></span>
            </div>
        </div>

        <!-- Refund Details -->
        <div class="info-section">
            <h2>Refund Details</h2>
            <div class="info-row">
                <span class="info-label">Original Amount:</span>
                <span class="info-value">{{ formatRupiah($booking->total_price) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Processing Fee (30%):</span>
                <span class="info-value" style="color: #dc3545;">- {{ formatRupiah($refundRequest->processing_fee) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Refund Amount:</span>
                <span class="info-value price">{{ formatRupiah($refundRequest->refund_amount) }}</span>
            </div>
        </div>

        <!-- Bank Details -->
        <div class="info-section">
            <h2>Bank Account Details</h2>
            <div class="info-row">
                <span class="info-label">Account Holder:</span>
                <span class="info-value">{{ $refundRequest->account_holder_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Bank Name:</span>
                <span class="info-value">{{ $refundRequest->bank_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Account Number:</span>
                <span class="info-value">{{ $refundRequest->account_number }}</span>
            </div>
        </div>

        <!-- Refund Reason -->
        <div class="info-section">
            <h2>Refund Reason</h2>
            <div class="reason-box">
                <p>{{ $refundRequest->reason }}</p>
            </div>
        </div>

        <!-- Request Date -->
        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Request Date:</span>
                <span class="info-value">{{ $refundRequest->created_at->format('F d, Y \a\t h:i A') }}</span>
            </div>
        </div>

        <!-- Action Button -->
        <div style="text-align: center;">
            <a href="{{ route('refund-requests.show', ['event' => $event->id, 'refundRequest' => $refundRequest->id]) }}" class="button">
                Review Refund Request
            </a>
        </div>

        <div class="footer">
            <p>This is an automated notification from BoothEase</p>
            <p>Please review this refund request at your earliest convenience</p>
        </div>
    </div>
</body>

</html>