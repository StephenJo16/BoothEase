<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Attendant Details - BoothEase</title>

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
$tenant = $booking->user;

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

// Calculate tenant's average rating
$tenantRatings = $tenant->ratingsReceived;
$averageRating = $tenantRatings->count() > 0 ? round($tenantRatings->avg('rating'), 1) : 0;
$totalRatings = $tenantRatings->count();
@endphp

<body class="bg-gray-50 min-h-screen font-['Instrument_Sans']">
    <!-- Navbar -->
    @include('components.navbar')

    <!-- Main Content -->
    <div class="min-h-screen py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Back Button -->
            @include('components.back-button', ['text' => 'Back to Event Details', 'url' => route('my-events.show', $event->id)])

            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Attendant Details</h1>
                        <p class="text-gray-600">Booking ID: ID-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Tenant Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Tenant Information</h2>
                        <div class="space-y-4">
                            <!-- Tenant Profile -->
                            <div class="flex items-start gap-4 pb-4 border-b border-gray-200">
                                <div class="flex-shrink-0">
                                    @if($tenant->avatar)
                                    <img src="{{ $tenant->avatar }}" alt="{{ $tenant->name }}" class="w-20 h-20 rounded-full object-cover">
                                    @else
                                    <div class="w-20 h-20 rounded-full bg-[#ff7700] flex items-center justify-center">
                                        <span class="text-white text-2xl font-bold">{{ substr($tenant->name, 0, 1) }}</span>
                                    </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-900">{{ $tenant->name }}</h3>
                                    <p class="text-gray-600">{{ $tenant->display_name }}</p>
                                    @if($tenant->business_category)
                                    <p class="text-sm text-gray-500 mt-1">
                                        <i class="fas fa-briefcase mr-1"></i>
                                        {{ ucfirst($tenant->business_category) }}
                                    </p>
                                    @endif

                                    <!-- Tenant Rating Display -->
                                    <div class="flex items-center mt-2">
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <=floor($averageRating))
                                                <i class="fas fa-star text-[#ff7700] text-sm"></i>
                                                @elseif($i - $averageRating < 1 && $i - $averageRating> 0)
                                                    <i class="fas fa-star-half-alt text-[#ff7700] text-sm"></i>
                                                    @else
                                                    <i class="far fa-star text-gray-300 text-sm"></i>
                                                    @endif
                                                    @endfor
                                        </div>
                                        <span class="ml-2 text-sm text-gray-600">
                                            {{ $averageRating > 0 ? number_format($averageRating, 1) : 'No ratings' }}
                                            @if($totalRatings > 0)
                                            ({{ $totalRatings }} {{ $totalRatings === 1 ? 'rating' : 'ratings' }})
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Email</h4>
                                    <p class="text-gray-900 flex items-center">
                                        <i class="fas fa-envelope text-[#ff7700] mr-2"></i>
                                        {{ $tenant->email }}
                                    </p>
                                </div>
                                @if($tenant->phone_number)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Phone Number</h4>
                                    <p class="text-gray-900 flex items-center">
                                        <i class="fas fa-phone text-[#ff7700] mr-2"></i>
                                        {{ formatPhoneNumber($tenant->phone_number) }}
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Event Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Event Information</h2>
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $event->title }}</h3>
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
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Booth Type</h4>
                                <p class="text-lg font-semibold text-gray-900">{{ ucfirst($booth->type ?? 'Standard') }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Booking Notes</h4>
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

                    <!-- Previous Ratings from Other Organizers -->
                    @if($tenantRatings->count() > 0)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">
                            Ratings from Other Organizers
                            <span class="text-sm font-normal text-gray-500">({{ $totalRatings }} {{ $totalRatings === 1 ? 'review' : 'reviews' }})</span>
                        </h2>

                        <div class="space-y-4">
                            @foreach($tenantRatings->take(5) as $rating)
                            <div class="border-b border-gray-200 pb-4 last:border-b-0 last:pb-0">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex items-center">
                                        <div class="flex">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <=$rating->rating)
                                                <i class="fas fa-star text-[#ff7700]"></i>
                                                @else
                                                <i class="far fa-star text-gray-300"></i>
                                                @endif
                                                @endfor
                                        </div>
                                        <span class="ml-2 text-sm font-medium text-gray-700">{{ $rating->rating }}/5</span>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $rating->created_at->format('M d, Y') }}</span>
                                </div>
                                @if($rating->feedback)
                                <p class="text-sm text-gray-600 italic">"{{ $rating->feedback }}"</p>
                                @endif
                                <p class="text-xs text-gray-500 mt-1">Event: {{ $rating->event->title ?? 'N/A' }}</p>
                            </div>
                            @endforeach

                            @if($tenantRatings->count() > 5)
                            <p class="text-sm text-gray-500 text-center pt-2">
                                And {{ $tenantRatings->count() - 5 }} more reviews...
                            </p>
                            @endif
                        </div>
                    </div>
                    @endif
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
                            <div class="border-t pt-4">
                                <div class="flex justify-between text-lg font-semibold">
                                    <span>Total Amount</span>
                                    <span class="text-[#ff7700]">{{ formatRupiah($booking->total_price) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    @if($booking->payment)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Payment Details</h2>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Method</span>
                                <span class="font-medium">{{ $booking->payment->formatted_payment_method ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Status</span>
                                @if($booking->payment->payment_status === 'completed')
                                <span class="text-green-600 font-medium">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Paid
                                </span>
                                @else
                                <span class="text-yellow-600 font-medium">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ ucfirst($booking->payment->payment_status) }}
                                </span>
                                @endif
                            </div>
                            @if($booking->payment->transaction_id)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Transaction ID</span>
                                <span class="font-medium text-sm break-all">{{ $booking->payment->transaction_id }}</span>
                            </div>
                            @endif
                            @if($booking->payment->payment_date)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Date</span>
                                <span class="font-medium">{{ $booking->payment->payment_date->format('d-m-Y') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Rating Section (only for completed bookings) -->
                    @if($booking->status === 'completed')
                    <div class="bg-white rounded-lg shadow-md p-6" id="rating-section">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Rate This Tenant</h2>

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
                                        placeholder="Share your experience with this tenant..."
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

            // Check if organizer has already rated this tenant
            fetch('{{ route("attendant.rating.check", ["event" => $event->id, "booking" => $booking->id]) }}', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || ''
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.has_rated && data.rating) {
                        // Organizer has already rated, show thank you message
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
                fetch('{{ route("attendant.rating.store", ["event" => $event->id, "booking" => $booking->id]) }}', {
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
</body>

</html>