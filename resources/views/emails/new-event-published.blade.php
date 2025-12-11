<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Event Alert</title>
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

        .header p {
            color: #666;
            margin: 10px 0 0 0;
            font-size: 14px;
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

        .event-banner {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .event-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin: 20px 0 10px 0;
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
            min-width: 150px;
        }

        .info-value {
            color: #333;
            text-align: right;
            flex: 1;
        }

        .category-badge {
            display: inline-block;
            background-color: #ff7700;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-top: 10px;
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
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            color: #666;
            font-size: 12px;
        }

        .footer a {
            color: #ff7700;
            text-decoration: none;
        }

        .description {
            color: #555;
            line-height: 1.8;
            margin: 15px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>New Event Alert!</h1>
            <p>A new event matching your business category is now available</p>
        </div>

        <!-- Alert Box -->
        <div class="alert-box">
            <strong>✨ Perfect Match!</strong>
            This event matches your business category: <strong>{{ $event->category->name ?? 'N/A' }}</strong>. Book your booth before spaces run out!
        </div>

        <!-- Event Title -->
        <h2 class="event-title">{{ $event->title }}</h2>
        <span class="category-badge">{{ $event->category->name ?? 'N/A' }}</span>

        <!-- Event Description -->
        @if($event->description)
        <div class="description">
            {{ Str::limit($event->description, 200) }}
        </div>
        @endif

        <!-- Event Details -->
        <div class="info-section">
            <h2>Event Details</h2>
            <div class="info-row">
                <span class="info-label">📅 Start Date</span>
                <span class="info-value">{{ $event->start_time->format('l, F j, Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">🕐 Start Time</span>
                <span class="info-value">{{ $event->start_time->format('H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">📅 End Date</span>
                <span class="info-value">{{ $event->end_time->format('l, F j, Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">🕐 End Time</span>
                <span class="info-value">{{ $event->end_time->format('H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">📍 Venue</span>
                <span class="info-value">{{ $event->venue ?? 'TBA' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">📌 Location</span>
                <span class="info-value">
                    {{ $event->city->name ?? '' }}{{ $event->city && $event->province ? ', ' : '' }}{{ $event->province->name ?? '' }}
                </span>
            </div>
            @if($event->registration_deadline)
            <div class="info-row">
                <span class="info-label">⏰ Registration Deadline</span>
                <span class="info-value">{{ $event->registration_deadline->format('F j, Y H:i') }}</span>
            </div>
            @endif
        </div>

        <!-- Call to Action -->
        <div style="text-align: center;">
            <a href="{{ route('events.show', $event->id) }}" class="button">
                View Event & Book Your Booth
            </a>
        </div>

        <!-- Greeting -->
        <p style="margin-top: 30px;">
            Hi <strong>{{ $tenant->display_name ?? $tenant->name }}</strong>,
        </p>
        <p>
            We thought you'd be interested in this new event! As a business in the <strong>{{ $tenant->category->name ?? 'N/A' }}</strong> category, this could be a great opportunity to showcase your products or services.
        </p>
        <p>
            Don't miss out spaces are limited and booths are allocated on a first-come, first-served basis.
        </p>

        <!-- Footer -->
        <div class="footer">
            <p>
                This is an automated notification from <strong>BoothEase</strong>.<br>
                You received this email because your business category matches this event.
            </p>
            <p>
                Need help? <a href="mailto:support@boothease.com">Contact Support</a>
            </p>
            <p style="margin-top: 15px; color: #999;">
                &copy; {{ date('Y') }} BoothEase. All rights reserved.
            </p>
        </div>
    </div>
</body>

</html>