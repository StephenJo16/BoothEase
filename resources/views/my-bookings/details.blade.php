<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Booking Details - BoothEase</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

@php
$event=$booking->booth->event;
$booth = $booking->booth;
$statusDisplay = getBookingStatusDisplay($booking->status);

// Format event dates and times
$dateDisplay = 'Schedule to be announced';
$timeDisplay = null;

if ($event->start_time && $event->end_time) {
$startDate = $event->start_time->format('d M Y');
$endDate = $event->end_time->format('d M Y');
$dateDisplay = $event->start_time->isSameDay($event->end_time) ? $startDate : "{$startDate} - {$endDate}";
$timeDisplay = $event->start_time->format('H:i') . ' - ' . $event->end_time->format('H:i');
} elseif ($event->start_time) {
$dateDisplay = $event->start_time->format('d M Y');
$timeDisplay = $event->start_time->format('H:i');
} elseif ($event->end_time) {
$dateDisplay = $event->end_time->format('d M Y');
$timeDisplay = $event->end_time->format('H:i');
}

// Calculate duration
$eventDuration = 0;
if ($event->start_time && $event->end_time) {
$eventDuration = floor($event->start_time->diffInDays($event->end_time)) + 1;
}
@endphp

<body class="bg-gray-50 min-h-screen font-['Instrument_Sans']">
    <!-- Navbar -->
    @include('components.navbar')

    <!-- Main Content -->
    <div class="min-h-screen py-8">

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Back Button -->
            @include('components.back-button', ['text' => 'Back to My Bookings', 'url' => route('my-bookings')])
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Booking Details</h1>
                        <p class="text-gray-600">Booking ID: ID-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $statusDisplay['class'] }}">
                            <i class="fas fa-{{ 
                                    $booking->status === 'confirmed' ? 'check-circle' : 
                                    ($booking->status === 'paid' ? 'credit-card' : 
                                    ($booking->status === 'ongoing' ? 'spinner fa-pulse' : 
                                    ($booking->status === 'completed' ? 'check-double' : 
                                    ($booking->status === 'pending' ? 'clock' : 
                                    ($booking->status === 'rejected' ? 'times-circle' : 'ban'))))) 
                                }} mr-2"></i>
                            {{ $statusDisplay['label'] }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Event Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Event Information</h2>
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $event->title }}</h3>
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-map-marker-alt mr-3 text-[#ff7700]"></i>
                                    <span class="text-gray-700">{{ $event->venue ?? 'Venue not specified' }}</span>
                                </div>
                                <div class="text-gray-700">
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-alt mr-3 text-[#ff7700]"></i>
                                        <span>{{ $dateDisplay }}</span>
                                    </div>
                                    @if($timeDisplay)
                                    <div class="mt-1 flex items-center">
                                        <i class="fa-regular fa-clock mr-3 text-[#ff7700]"></i>
                                        <span>{{ $timeDisplay }}</span>
                                    </div>
                                    @endif
                                </div>
                                <p class="text-gray-600 leading-relaxed mt-4">
                                    {{ $event->description ?? 'No description available' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Booth Details -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Booth Details</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Booth Number</h4>
                                <p class="text-lg font-semibold text-gray-900">{{ $booth->number }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Booth Size</h4>
                                <p class="text-lg font-semibold text-gray-900">{{ $booth->size ?? 'Not specified' }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Category</h4>
                                <p class="text-lg font-semibold text-gray-900">{{ $event->category->name ?? 'General' }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Notes</h4>
                                <p class="text-lg font-semibold text-gray-900">{{ $booking->notes ?? '-' }}</p>
                            </div>
                        </div>

                        @if($booth->amenities)
                        <!-- Included Features -->
                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Included Features</h4>
                            <div class="grid grid-cols-2 gap-3">
                                @php
                                $amenitiesArray = is_string($booth->amenities) ? json_decode($booth->amenities, true) : $booth->amenities;
                                $amenitiesArray = $amenitiesArray ?? [];
                                @endphp
                                @foreach($amenitiesArray as $amenity)
                                <div class="flex items-center">
                                    <i class="fas fa-check text-[#ff7700] mr-2"></i>
                                    <span class="text-sm text-gray-700">{{ $amenity }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Booth Layout -->
                    <!-- <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Booth Layout</h2>
                        <div class="bg-gray-100 rounded-lg p-8 text-center">
                            <div class="bg-white border-2 border-[#ff7700] rounded-lg p-6 inline-block">
                                <div class="text-[#ff7700] text-4xl mb-2">
                                    <i class="fas fa-store"></i>
                                </div>
                                <div class="text-lg font-semibold text-gray-900">Booth A01</div>
                                <div class="text-sm text-gray-600">3m × 3m</div>
                            </div>
                            <p class="text-sm text-gray-600 mt-4">Hall A - Ground Floor Layout</p>
                        </div>
                    </div> -->

                    <!-- Contact Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Event Organizer Contact</h2>
                        <div class="space-y-3">
                            @if($event->user && $event->user->email)
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-[#ff7700] mr-3"></i>
                                <span class="text-gray-700">{{ $event->user->email }}</span>
                            </div>
                            @endif
                            @if($event->user && $event->user->phone_number)
                            <div class="flex items-center">
                                <i class="fas fa-phone text-[#ff7700] mr-3"></i>
                                <span class="text-gray-700">{{ formatPhoneNumber($event->user->phone_number) }}</span>
                            </div>
                            @endif
                            @if(!$event->user || (!$event->user->email && !$event->user->phone_number))
                            <div class="text-gray-600">
                                Contact information not available
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Booking Summary -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Booking Summary</h2>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Booking Date</span>
                                <span class="font-medium">{{ $booking->booking_date->format('d-m-Y') }}</span>
                            </div>
                            @if($eventDuration > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Duration</span>
                                <span class="font-medium">{{ $eventDuration }} Day{{ $eventDuration > 1 ? 's' : '' }}</span>
                            </div>
                            @endif
                            @if($booth->type)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Booth Type</span>
                                <span class="font-medium">{{ ucfirst($booth->type) }}</span>
                            </div>
                            @endif
                            <div class="border-t pt-4">
                                <div class="flex justify-between text-lg font-semibold">
                                    <span>Total Amount</span>
                                    <span class="text-[#ff7700]">{{ formatRupiah($booking->total_price) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rating Section (only for completed bookings) -->
                    @if($booking->status === 'completed')
                    <div class="bg-white rounded-lg shadow-md p-6" id="rating-section">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Rate Your Experience</h2>

                        <div id="rating-form">
                            <div class="space-y-4">
                                <!-- Star Rating -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Your Rating</label>
                                    <div class="flex gap-2" id="star-rating">
                                        <button type="button" class="star-btn text-3xl text-gray-300 hover:text-[#ff7700] transition-colors duration-200" data-rating="1">
                                            <i class="far fa-star"></i>
                                        </button>
                                        <button type="button" class="star-btn text-3xl text-gray-300 hover:text-[#ff7700] transition-colors duration-200" data-rating="2">
                                            <i class="far fa-star"></i>
                                        </button>
                                        <button type="button" class="star-btn text-3xl text-gray-300 hover:text-[#ff7700] transition-colors duration-200" data-rating="3">
                                            <i class="far fa-star"></i>
                                        </button>
                                        <button type="button" class="star-btn text-3xl text-gray-300 hover:text-[#ff7700] transition-colors duration-200" data-rating="4">
                                            <i class="far fa-star"></i>
                                        </button>
                                        <button type="button" class="star-btn text-3xl text-gray-300 hover:text-[#ff7700] transition-colors duration-200" data-rating="5">
                                            <i class="far fa-star"></i>
                                        </button>
                                    </div>
                                    <input type="hidden" id="rating-value" value="0">
                                    <p class="text-sm text-red-600 mt-1 hidden" id="rating-error">Please select a rating</p>
                                </div>

                                <!-- Feedback/Comment (Optional) -->
                                <div>
                                    <label for="feedback" class="block text-sm font-medium text-gray-700 mb-2">
                                        Comment <span class="text-gray-500 text-xs">(Optional)</span>
                                    </label>
                                    <textarea
                                        id="feedback"
                                        rows="4"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff7700] focus:border-transparent resize-none"
                                        placeholder="Share your experience with this event..."
                                        maxlength="1000"></textarea>
                                    <p class="text-xs text-gray-500 mt-1">Maximum 1000 characters</p>
                                </div>

                                <!-- Submit Button -->
                                <button
                                    type="button"
                                    id="submit-rating-btn"
                                    class="w-full bg-[#ff7700] hover:bg-[#e66600] text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    Submit Rating
                                </button>
                            </div>
                        </div>

                        <!-- Thank You Message (Hidden initially) -->
                        <div id="rating-thank-you" class="hidden text-center py-8">
                            <div class="text-[#ff7700] text-5xl mb-4">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Thank You!</h3>
                            <p class="text-gray-600 mb-4">Your rating has been submitted successfully.</p>

                            <!-- Display submitted rating -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <p class="text-sm font-medium text-gray-700 mb-2">Your Rating</p>
                                <div class="flex justify-center gap-1 mb-4" id="submitted-stars">
                                    <!-- Stars will be populated by JavaScript -->
                                </div>
                                <div id="submitted-feedback" class="text-left bg-gray-50 rounded-lg p-4 text-sm text-gray-700 hidden">
                                    <!-- Feedback will be populated by JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Payment Details -->
                    @if(in_array($booking->status, ['confirmed', 'cancelled', 'paid', 'ongoing', 'completed']))
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Payment Details</h2>
                        <div class="space-y-4">
                            @if($booking->payment)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Method</span>
                                <span class="font-medium">{{ $booking->payment->formatted_payment_method ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Status</span>
                                @if($booking->payment->payment_status === 'completed')
                                <span class="text-green-600 font-medium" id="payment-status-badge">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Paid
                                </span>
                                @else
                                <span class="text-yellow-600 font-medium" id="payment-status-badge">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ ucfirst($booking->payment->payment_status) }}
                                </span>
                                @endif
                            </div>
                            @if($booking->payment->transaction_id)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Transaction ID</span>
                                <span class="font-medium text-sm">{{ $booking->payment->transaction_id }}</span>
                            </div>
                            @endif
                            @if($booking->payment->payment_date)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Date</span>
                                <span class="font-medium">{{ $booking->payment->payment_date->format('d-m-Y') }}</span>
                            </div>
                            @endif

                            @if($booking->payment->payment_status === 'pending')
                            <!-- Check Payment Status Button -->
                            <div class="pt-4 border-t">
                                <button id="check-payment-btn"
                                    class="w-full bg-blue-50 hover:bg-blue-100 text-blue-600 font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                    <i class="fas fa-sync-alt mr-2"></i>
                                    <span id="check-btn-text">Check Payment Status</span>
                                </button>
                                <p class="text-xs text-gray-500 text-center mt-2">
                                    Click to verify your payment status with the payment gateway
                                </p>
                            </div>
                            @endif
                            @else
                            <div class="text-gray-600">
                                @if($booking->status === 'confirmed')
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                                    <i class="fas fa-exclamation-circle text-yellow-600 mb-2 text-2xl"></i>
                                    <p class="text-yellow-800 font-medium">Payment Required</p>
                                    <p class="text-yellow-700 text-sm mt-1">Please complete the payment to secure your booking</p>
                                </div>
                                @else
                                Payment information not available
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Booking Timeline -->
                    <!-- <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Booking Timeline</h2>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-check text-green-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Booking Confirmed</p>
                                    <p class="text-xs text-gray-500">18-10-2025, 14:30</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-credit-card text-green-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Payment Completed</p>
                                    <p class="text-xs text-gray-500">18-10-2025, 14:15</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-plus text-blue-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Booking Created</p>
                                    <p class="text-xs text-gray-500">18-10-2025, 14:00</p>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <!-- Action Buttons -->
                    @if($booking->status === 'confirmed' || $booking->status === 'ongoing')
                    <div class="space-y-3">
                        @if(!$booking->payment || $booking->payment->payment_status !== 'completed')
                        <a href="{{ route('payment.create', $booking->id) }}">
                            <button class="mb-2 w-full bg-[#ff7700] hover:bg-[#e66600] text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                                <i class="fas fa-credit-card mr-2"></i>
                                Continue to Payment
                            </button>
                        </a>
                        @else
                        <a href="{{ route('booking.invoice', $booking->id) }}">
                            <button class="mb-2 w-full bg-[#ff7700] hover:bg-[#e66600] text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                                <i class="fas fa-download mr-2"></i>
                                Download Invoice
                            </button>
                        </a>
                        @if($event->refundable && !$booking->refundRequest)
                        <a href="{{ route('request-refund', ['booking' => $booking->id]) }}">
                            <button class="w-full bg-red-50 hover:bg-red-100 text-red-600 font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                                <i class="fas fa-undo mr-2"></i>
                                Request Refund
                            </button>
                        </a>
                        @endif
                        @if($booking->refundRequest)
                        @if($booking->refundRequest->isPending())
                        <div class="w-full bg-yellow-50 text-yellow-600 font-medium py-3 px-4 rounded-lg border border-yellow-200 text-center">
                            <i class="fas fa-clock mr-2"></i>
                            Refund Request Pending
                        </div>
                        @elseif($booking->refundRequest->isApproved())
                        <div class="w-full bg-green-50 text-green-600 font-medium py-3 px-4 rounded-lg border border-green-200 text-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            Refund Approved
                        </div>
                        @elseif($booking->refundRequest->isRejected())
                        <div class="w-full bg-red-50 border border-red-200 rounded-lg py-3 px-4">
                            <div class="text-red-600 font-medium text-center">
                                <i class="fas fa-times-circle mr-2"></i>
                                Refund Rejected
                            </div>
                            @if($booking->refundRequest->rejection_reason)
                            <p class="text-xs text-red-700 text-center mt-2 break-words">
                                {{ $booking->refundRequest->rejection_reason }}
                            </p>
                            @endif
                        </div>
                        @endif
                        @endif
                        @endif
                    </div>
                    @elseif($booking->status === 'paid' || $booking->status === 'completed')
                    <div class="space-y-3">
                        <a href="{{ route('booking.invoice', $booking->id) }}">
                            <button class="mb-2 w-full bg-[#ff7700] hover:bg-[#e66600] text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                                <i class="fas fa-download mr-2"></i>
                                Download Invoice
                            </button>
                        </a>
                        @if($booking->status !== 'completed' && $event->refundable && !$booking->refundRequest)
                        <a href="{{ route('request-refund', ['booking' => $booking->id]) }}">
                            <button class="w-full bg-red-50 hover:bg-red-100 text-red-600 font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                                <i class="fas fa-undo mr-2"></i>
                                Request Refund
                            </button>
                        </a>
                        @endif
                        @if($booking->refundRequest)
                        @if($booking->refundRequest->isPending())
                        <div class="w-full bg-yellow-50 text-yellow-600 font-medium py-3 px-4 rounded-lg border border-yellow-200 text-center">
                            <i class="fas fa-clock mr-2"></i>
                            Refund Request Pending
                        </div>
                        @elseif($booking->refundRequest->isApproved())
                        <div class="w-full bg-green-50 text-green-600 font-medium py-3 px-4 rounded-lg border border-green-200 text-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            Refund Approved
                        </div>
                        @elseif($booking->refundRequest->isRejected())
                        <div class="w-full bg-red-50 border border-red-200 rounded-lg py-3 px-4">
                            <div class="text-red-600 font-medium text-center">
                                <i class="fas fa-times-circle mr-2"></i>
                                Refund Rejected
                            </div>
                            @if($booking->refundRequest->rejection_reason)
                            <p class="text-xs text-red-700 text-center mt-2 break-words">
                                {{ $booking->refundRequest->rejection_reason }}
                            </p>
                            @endif
                        </div>
                        @endif
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('components.footer')

    <!-- Rating System Script -->
    @if($booking->status === 'completed')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const starButtons = document.querySelectorAll('.star-btn');
            const ratingValue = document.getElementById('rating-value');
            const ratingError = document.getElementById('rating-error');
            const submitBtn = document.getElementById('submit-rating-btn');
            const feedbackInput = document.getElementById('feedback');
            const ratingForm = document.getElementById('rating-form');
            const thankYouMessage = document.getElementById('rating-thank-you');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            let selectedRating = 0;

            // Check if user has already rated
            fetch('{{ route("rating.check", $booking->id) }}', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || ''
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.has_rated && data.rating) {
                        // User has already rated, show thank you message
                        ratingForm.classList.add('hidden');
                        thankYouMessage.classList.remove('hidden');

                        // Display the submitted rating stars
                        const submittedStars = document.getElementById('submitted-stars');
                        const userRating = data.rating.rating;

                        for (let i = 1; i <= 5; i++) {
                            const starIcon = document.createElement('i');
                            if (i <= userRating) {
                                starIcon.className = 'fas fa-star text-[#ff7700] text-2xl';
                            } else {
                                starIcon.className = 'far fa-star text-gray-300 text-2xl';
                            }
                            submittedStars.appendChild(starIcon);
                        }

                        // Display the feedback if available
                        if (data.rating.feedback) {
                            const feedbackDiv = document.getElementById('submitted-feedback');
                            feedbackDiv.textContent = data.rating.feedback;
                            feedbackDiv.classList.remove('hidden');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error checking rating status:', error);
                });

            // Star rating interaction
            starButtons.forEach(button => {
                button.addEventListener('click', function() {
                    selectedRating = parseInt(this.getAttribute('data-rating'));
                    ratingValue.value = selectedRating;
                    ratingError.classList.add('hidden');

                    // Update star display
                    updateStars(selectedRating);
                });

                // Hover effect
                button.addEventListener('mouseenter', function() {
                    const hoverRating = parseInt(this.getAttribute('data-rating'));
                    updateStars(hoverRating);
                });
            });

            // Reset stars on mouse leave
            document.getElementById('star-rating').addEventListener('mouseleave', function() {
                updateStars(selectedRating);
            });

            function updateStars(rating) {
                starButtons.forEach((btn, index) => {
                    const star = btn.querySelector('i');
                    if (index < rating) {
                        star.classList.remove('far', 'text-gray-300');
                        star.classList.add('fas', 'text-[#ff7700]');
                    } else {
                        star.classList.remove('fas', 'text-[#ff7700]');
                        star.classList.add('far', 'text-gray-300');
                    }
                });
            }

            // Submit rating
            submitBtn.addEventListener('click', function() {
                const rating = parseInt(ratingValue.value);
                const feedback = feedbackInput.value.trim();

                // Validate rating
                if (rating < 1 || rating > 5) {
                    ratingError.classList.remove('hidden');
                    return;
                }

                // Disable button and show loading
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Submitting...';

                // Submit the rating
                fetch('{{ route("rating.store", $booking->id) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken || ''
                        },
                        body: JSON.stringify({
                            rating: rating,
                            feedback: feedback || null
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show thank you message
                            ratingForm.classList.add('hidden');
                            thankYouMessage.classList.remove('hidden');

                            // Display the submitted rating stars
                            const submittedStars = document.getElementById('submitted-stars');
                            submittedStars.innerHTML = ''; // Clear any existing stars

                            for (let i = 1; i <= 5; i++) {
                                const starIcon = document.createElement('i');
                                if (i <= rating) {
                                    starIcon.className = 'fas fa-star text-[#ff7700] text-2xl';
                                } else {
                                    starIcon.className = 'far fa-star text-gray-300 text-2xl';
                                }
                                submittedStars.appendChild(starIcon);
                            }

                            // Display the feedback if available
                            if (feedback) {
                                const feedbackDiv = document.getElementById('submitted-feedback');
                                feedbackDiv.textContent = feedback;
                                feedbackDiv.classList.remove('hidden');
                            }
                        } else {
                            alert('❌ ' + (data.message || 'Failed to submit rating'));
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Submit Rating';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('❌ An error occurred while submitting your rating');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Submit Rating';
                    });
            });
        });
    </script>
    @endif

    <!-- Check Payment Status Script -->
    @if($booking->payment && $booking->payment->payment_status === 'pending')
    <script>
        document.getElementById('check-payment-btn').addEventListener('click', function() {
            const button = this;
            const buttonText = document.getElementById('check-btn-text');
            const statusBadge = document.getElementById('payment-status-badge');

            // Disable button and show loading
            button.disabled = true;
            buttonText.innerHTML = 'Checking...';
            button.querySelector('i').classList.add('fa-spin');

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            // Call check status endpoint
            fetch('{{ route("payment.check-status", $booking->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || ''
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.payment_status === 'completed') {
                            // Payment is completed - reload page to show updated status
                            alert('✅ ' + data.message);
                            window.location.reload();
                        } else {
                            // Still pending
                            alert('⏳ ' + data.message);
                            button.disabled = false;
                            buttonText.innerHTML = 'Check Payment Status';
                            button.querySelector('i').classList.remove('fa-spin');
                        }
                    } else {
                        alert('❌ ' + (data.message || 'Failed to check payment status'));
                        button.disabled = false;
                        buttonText.innerHTML = 'Check Payment Status';
                        button.querySelector('i').classList.remove('fa-spin');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('❌ An error occurred while checking payment status');
                    button.disabled = false;
                    buttonText.innerHTML = 'Check Payment Status';
                    button.querySelector('i').classList.remove('fa-spin');
                });
        });
    </script>
    @endif
</body>

</html>