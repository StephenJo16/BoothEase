<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
// Helper to format rupiah with dot thousand separators
if (!function_exists('formatRupiah')) {
function formatRupiah($value) {
$digits = preg_replace('/\D/', '', (string) $value);
$num = $digits === '' ? 0 : intval($digits);
return 'Rp' . number_format($num, 0, ',', '.');
}
}

if (!function_exists('formatPhoneNumber')) {
function formatPhoneNumber($number) {
$digits = preg_replace('/\D+/', '', (string) $number);
if ($digits === '') {
return $number;
}

if (substr($digits, 0, 2) === '62') {
$country = '+62';
$rest = substr($digits, 2);
} elseif (substr($digits, 0, 1) === '0') {
$country = '+62';
$rest = ltrim($digits, '0');
} else {
return $number;
}

if ($rest === '') {
return $country;
}

if (strlen($rest) <= 3) {
    $formattedRest=$rest;
    } else {
    $firstBlock=substr($rest, 0, 3);
    $remaining=substr($rest, 3);
    $chunks=str_split($remaining, 4);
    $formattedRest=$firstBlock . ($chunks ? '-' . implode('-', $chunks) : '' );
    }

    return trim($country . ' ' . $formattedRest);
    }
    }

    // Helper to format status with proper label and color
    function getStatusDisplay($status) {
    $statusMap=[ 'pending'=> ['label' => 'Pending', 'color' => 'bg-yellow-100 text-yellow-800'],
    'confirmed' => ['label' => 'Confirmed', 'color' => 'bg-green-100 text-green-800'],
    'rejected' => ['label' => 'Rejected', 'color' => 'bg-red-100 text-red-800'],
    'cancelled' => ['label' => 'Cancelled', 'color' => 'bg-gray-100 text-gray-800'],
    ];

    return $statusMap[$status] ?? ['label' => ucfirst($status), 'color' => 'bg-gray-100 text-gray-800'];
    }

    $event = $booking->booth->event;
    $booth = $booking->booth;
    $statusDisplay = getStatusDisplay($booking->status);

    // Format event dates
    $eventDates = '';
    if ($event->start_time && $event->end_time) {
    $start = $event->start_time;
    $end = $event->end_time;
    $eventDates = $start->format('d') . ' - ' . $end->format('d F Y');
    }

    // Calculate duration
    $eventDuration = 0;
    if ($event->start_time && $event->end_time) {
    $start = $event->start_time;
    $end = $event->end_time;
    $eventDuration = floor($start->diffInDays($end)) + 1;
    $eventDates = $start->format('F d') . ' - ' . $end->format('d, Y') . ' (' . $eventDuration . ' days)';
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
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $statusDisplay['color'] }}">
                                <i class="fas fa-{{ $booking->status === 'confirmed' ? 'check-circle' : ($booking->status === 'pending' ? 'clock' : ($booking->status === 'rejected' ? 'times-circle' : 'ban')) }} mr-2"></i>
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
                                    <div class="flex items-center mb-4">
                                        <i class="fas fa-calendar-alt mr-3 text-[#ff7700]"></i>
                                        <span class="text-gray-700">{{ $eventDates ?: 'Dates not specified' }}</span>
                                    </div>
                                    <p class="text-gray-600 leading-relaxed">
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

                    <!-- Payment Details -->
                    @if(in_array($booking->status, ['confirmed', 'cancelled']))
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Payment Details</h2>
                        <div class="space-y-4">
                            @if($booking->payment)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Method</span>
                                <span class="font-medium">{{ ucfirst($booking->payment->payment_method ?? 'N/A') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Status</span>
                                @if($booking->payment->status === 'completed')
                                <span class="text-green-600 font-medium">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Paid
                                </span>
                                @else
                                <span class="text-yellow-600 font-medium">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ ucfirst($booking->payment->status) }}
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
                            @else
                            <div class="text-gray-600">
                                Payment information not available
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
                    @if($booking->status === 'confirmed')
                    <div class="space-y-3">
                        <button class="mb-2 w-full bg-[#ff7700] hover:bg-[#e66600] text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                            <i class="fas fa-download mr-2"></i>
                            Download Invoice
                        </button>
                        <a href="{{ route('request-refund') }}">
                            <button class="w-full bg-red-50 hover:bg-red-100 text-red-600 font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                                <i class="fas fa-undo mr-2"></i>
                                Request Refund
                            </button>
                        </a>
                    </div>
                    @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        @include('components.footer')
    </body>

</html>
