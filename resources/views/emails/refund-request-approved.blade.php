<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Request Approved</title>
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
            border-bottom: 3px solid #28a745;
        }

        .header h1 {
            color: #28a745;
            margin: 0;
            font-size: 28px;
        }

        .success-box {
            background-color: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .success-box strong {
            color: #155724;
            display: block;
            margin-bottom: 5px;
        }

        .success-box p {
            color: #155724;
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

        .price {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }

        .highlight-box {
            background-color: #e8f5e9;
            border: 2px solid #28a745;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }

        .highlight-box h3 {
            color: #28a745;
            margin: 0 0 10px 0;
            font-size: 20px;
        }

        .timeline {
            background-color: #f8f9fa;
            border-left: 3px solid #28a745;
            padding: 15px;
            margin: 20px 0;
        }

        .timeline-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-icon {
            width: 20px;
            height: 20px;
            background-color: #28a745;
            border-radius: 50%;
            margin-right: 10px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .timeline-content {
            flex: 1;
        }

        .timeline-content p {
            margin: 0;
            font-size: 14px;
            color: #333;
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
            background-color: #28a745;
            color: #fff;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>✅ Refund Request Approved</h1>
        </div>

        <div class="success-box">
            <strong>Great News!</strong>
            <p>Your refund request has been approved by the event organizer. The refund will be processed to your bank account within 5-10 business days.</p>
        </div>

        <!-- Refund Amount Highlight -->
        <div class="highlight-box">
            <h3>Refund Amount</h3>
            <div class="price">{{ formatRupiah($refundRequest->refund_amount) }}</div>
            <p style="margin: 10px 0 0 0; color: #666; font-size: 14px;">Will be transferred to your account</p>
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
                <span class="info-label">Status:</span>
                <span class="info-value"><span class="badge">CANCELLED</span></span>
            </div>
        </div>

        <!-- Refund Breakdown -->
        <div class="info-section">
            <h2>Refund Breakdown</h2>
            <div class="info-row">
                <span class="info-label">Original Booking Amount:</span>
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
            <h2>Refund Destination</h2>
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

        <!-- Timeline -->
        <div class="info-section">
            <h2>What Happens Next?</h2>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-icon"></div>
                    <div class="timeline-content">
                        <p><strong>Step 1:</strong> Your refund has been approved</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-icon" style="background-color: #ffc107;"></div>
                    <div class="timeline-content">
                        <p><strong>Step 2:</strong> Refund processing (5-10 business days)</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-icon" style="background-color: #6c757d;"></div>
                    <div class="timeline-content">
                        <p><strong>Step 3:</strong> Funds will appear in your bank account</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Important Information -->
        <div class="info-section">
            <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; border-radius: 4px;">
                <h3 style="color: #856404; margin: 0 0 10px 0; font-size: 16px;">📌 Important Notes</h3>
                <ul style="margin: 0; padding-left: 20px; color: #856404; font-size: 14px;">
                    <li>The refund will be processed within 5-10 business days</li>
                    <li>The amount will be transferred to the bank account you provided</li>
                    <li>Your booking has been cancelled and the booth is now available for others</li>
                    <li>If you don't receive the refund within 10 business days, please contact support</li>
                </ul>
            </div>
        </div>

        <div class="footer">
            <p>Thank you for using BoothEase</p>
            <p>If you have any questions, please contact our support team</p>
        </div>
    </div>
</body>

</html>