<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoiceNumber }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }

        .header {
            margin-bottom: 30px;
            border-bottom: 3px solid #ff7700;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #ff7700;
            font-size: 28px;
            margin-bottom: 5px;
        }

        .header .tagline {
            color: #666;
            font-size: 11px;
        }

        .invoice-info {
            margin-bottom: 30px;
        }

        .invoice-info table {
            width: 100%;
        }

        .invoice-info td {
            padding: 5px 0;
        }

        .invoice-info .label {
            font-weight: bold;
            color: #666;
            width: 150px;
        }

        .section-title {
            background-color: #ff7700;
            color: white;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .info-row {
            display: table-row;
        }

        .info-cell {
            display: table-cell;
            padding: 8px 12px;
            border: 1px solid #ddd;
        }

        .info-label {
            font-weight: bold;
            color: #666;
            width: 40%;
            background-color: #f9f9f9;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            color: #666;
        }

        .items-table td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .total-section {
            margin-top: 20px;
            text-align: right;
        }

        .total-row {
            margin-bottom: 8px;
        }

        .total-label {
            display: inline-block;
            width: 150px;
            font-weight: bold;
            color: #666;
        }

        .total-amount {
            display: inline-block;
            width: 200px;
            text-align: right;
            font-weight: bold;
        }

        .grand-total {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #333;
        }

        .grand-total .total-label,
        .grand-total .total-amount {
            font-size: 16px;
            color: #ff7700;
        }

        .payment-status {
            display: inline-block;
            padding: 5px 15px;
            background-color: #4CAF50;
            color: white;
            border-radius: 3px;
            font-weight: bold;
            margin-left: 10px;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
            text-align: center;
        }

        .notes {
            background-color: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #ff7700;
            margin-top: 20px;
            font-size: 11px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/boothease-logo-cropped.jpg'))) }}" alt="BoothEase Logo" style="max-width: 200px; height: auto;">
    </div>

    <!-- Invoice Information -->
    <div class="invoice-info">
        <table>
            <tr>
                <td class="label">INVOICE NUMBER:</td>
                <td><strong>{{ $invoiceNumber }}</strong></td>
                <td class="label" style="text-align: right;">DATE:</td>
                <td style="text-align: right;">{{ $invoiceDate->format('d M Y') }}</td>
            </tr>
            <tr>
                <td class="label">BOOKING ID:</td>
                <td>ID-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</td>
                <td class="label" style="text-align: right;">STATUS:</td>
                <td style="text-align: right;">
                    <span class="payment-status">PAID</span>
                </td>
            </tr>
        </table>
    </div>

    <!-- Bill To Section -->
    <div class="section-title">BILL TO</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-cell info-label">Customer Name</div>
            <div class="info-cell">{{ $user->display_name ?? $user->name }}</div>
        </div>
        <div class="info-row">
            <div class="info-cell info-label">Business Name</div>
            <div class="info-cell">{{ $user->name }}</div>
        </div>
        <div class="info-row">
            <div class="info-cell info-label">Email</div>
            <div class="info-cell">{{ $user->email }}</div>
        </div>
        <div class="info-row">
            <div class="info-cell info-label">Phone Number</div>
            <div class="info-cell">{{ $user->phone_number }}</div>
        </div>
    </div>

    <!-- Event Details Section -->
    <div class="section-title">EVENT DETAILS</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-cell info-label">Event Name</div>
            <div class="info-cell">{{ $event->title }}</div>
        </div>
        <div class="info-row">
            <div class="info-cell info-label">Event Date</div>
            <div class="info-cell">
                @if($event->start_time && $event->end_time)
                {{ $event->start_time->format('d M Y') }} - {{ $event->end_time->format('d M Y') }}
                @else
                To be announced
                @endif
            </div>
        </div>
        <div class="info-row">
            <div class="info-cell info-label">Location</div>
            <div class="info-cell">{{ $event->venue ?? 'To be confirmed' }}</div>
        </div>
        <div class="info-row">
            <div class="info-cell info-label">Organizer</div>
            <div class="info-cell">{{ $event->user->name ?? 'N/A' }}</div>
        </div>
    </div>

    <!-- Booking Details -->
    <div class="section-title">BOOKING DETAILS</div>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 50%;">Description</th>
                <th style="width: 15%; text-align: center;">Booth Number</th>
                <th style="width: 15%; text-align: center;">Size</th>
                <th style="width: 20%; text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>{{ $event->title }}</strong><br>
                    Booth Type: {{ ucfirst($booth->type ?? 'Standard') }}<br>
                    @if($booking->notes)
                    <em style="color: #666; font-size: 11px;">Note: {{ $booking->notes }}</em>
                    @endif
                </td>
                <td style="text-align: center;">{{ $booth->number }}</td>
                <td style="text-align: center;">{{ $booth->size ?? 'N/A' }}</td>
                <td style="text-align: right;">
                    Rp{{ number_format($booking->total_price, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Total Section -->
    <div class="total-section">
        <div class="total-row">
            <span class="total-label">Subtotal:</span>
            <span class="total-amount">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</span>
        </div>
        <div class="total-row grand-total">
            <span class="total-label">TOTAL:</span>
            <span class="total-amount">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Payment Information -->
    <div class="section-title">PAYMENT INFORMATION</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-cell info-label">Payment Method</div>
            <div class="info-cell">{{ $payment->formatted_payment_method ?? 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-cell info-label">Transaction ID</div>
            <div class="info-cell">{{ $payment->transaction_id ?? 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-cell info-label">Payment Date</div>
            <div class="info-cell">{{ $payment->updated_at ? $payment->updated_at->format('d M Y, H:i') : 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-cell info-label">Payment Status</div>
            <div class="info-cell"><strong style="color: #4CAF50;">COMPLETED</strong></div>
        </div>
    </div>

    <!-- Notes -->
    @if($event->description || $booking->notes)
    <div class="notes">
        <strong>Additional Notes:</strong><br>
        @if($event->description)
        {{ $event->description }}<br>
        @endif
        @if($booking->notes)
        <em>Booking Notes: {{ $booking->notes }}</em>
        @endif
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Thank you for booking with BoothEase!</p>
        <p>This is a computer-generated invoice and does not require a signature.</p>
        <p>For any queries, please contact the event organizer at {{ $event->user->email ?? 'support@boothease.com' }}</p>
    </div>
</body>

</html>