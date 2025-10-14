<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Bookings - BoothEase</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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

// Helper to format status with proper label and color
function getStatusDisplay($status) {
$statusMap = [
'pending' => ['label' => 'Pending', 'color' => 'bg-yellow-100 text-yellow-800'],
'confirmed' => ['label' => 'Confirmed', 'color' => 'bg-green-100 text-green-800'],
'rejected' => ['label' => 'Rejected', 'color' => 'bg-red-100 text-red-800'],
'cancelled' => ['label' => 'Cancelled', 'color' => 'bg-gray-100 text-gray-800'],
];

return $statusMap[$status] ?? ['label' => ucfirst($status), 'color' => 'bg-gray-100 text-gray-800'];
}

// Filter Tabs Data
$filterTabs = [
['name' => 'All Bookings', 'active' => true],
['name' => 'Confirmed', 'active' => false],
['name' => 'Rejected', 'active' => false],
['name' => 'Cancelled', 'active' => false]
];
@endphp

<body class="bg-gray-50 min-h-screen">
    <!-- Navbar -->
    @include('components.navbar')

    <!-- Main Content -->
    <div class="min-h-screen mb-8">
        <!-- Header -->
        @include('components.header', ['title' => 'My Bookings', 'subtitle' => 'Manage Your Booth Reservations'])
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Success/Error Messages -->
            @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg relative" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3 text-xl"></i>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg relative" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-3 text-xl"></i>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            </div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Bookings -->
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-left">
                            <p class="text-sm text-gray-600 mb-1">Total Bookings</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $totalBookings }}</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-store text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Confirmed -->
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-left">
                            <p class="text-sm text-gray-600 mb-1">Confirmed</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $confirmedBookings }}</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Spent -->
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-left">
                            <p class="text-sm text-gray-600 mb-1">Total Spent</p>
                            <p class="text-3xl font-bold text-gray-900">{{ formatRupiah($totalSpent) }}</p>
                        </div>
                        <div class="bg-orange-100 p-3 rounded-full">
                            <i class="fas fa-money-bill text-[#ff7700] text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Tabs -->
            <div class="bg-white rounded-lg shadow-md mb-6">
                @include('components.tabs', ['tabs' => $filterTabs])
            </div>

            <!-- Booking Cards -->
            <div class="space-y-6">
                @forelse($bookings as $booking)
                @php
                $event = $booking->booth->event;
                $statusDisplay = getStatusDisplay($booking->status);

                // Format event dates
                $eventDates = '';
                if ($event->start_time && $event->end_time) {
                $start = $event->start_time;
                $end = $event->end_time;
                $eventDates = $start->format('d') . ' - ' . $end->format('d F Y');
                }
                @endphp

                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $event->title }}</h3>
                                <div class="flex items-center mb-1">
                                    <i class="fas fa-map-marker-alt mr-2 text-[#ff7700]"></i>
                                    <p class="text-sm text-gray-600">{{ $event->venue ?? 'Venue not specified' }}</p>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt mr-2 text-[#ff7700]"></i>
                                    <p class="text-sm text-gray-600">{{ $eventDates ?: 'Dates not specified' }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusDisplay['color'] }}">
                                {{ $statusDisplay['label'] }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                            <!-- Booth Details -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Booth Details</h4>
                                <p class="text-sm text-gray-900 font-medium">{{ $booking->booth->number }}</p>
                                @if($booking->booth->type)
                                <p class="text-xs text-gray-600">{{ ucfirst($booking->booth->type) }} Type</p>
                                @endif
                            </div>

                            <!-- Booking Date -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Booking Date</h4>
                                <p class="text-sm text-gray-900">{{ $booking->booking_date->format('d-m-Y') }}</p>
                            </div>

                            <!-- Total Price -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Total Price</h4>
                                <p class="text-sm font-semibold text-[#ff7700]">{{ formatRupiah($booking->total_price) }}</p>
                            </div>

                            <!-- Booking ID -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Booking ID</h4>
                                <p class="text-sm text-gray-900">ID-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>

                        <!-- Booth Information -->
                        @if($booking->booth->size || $booking->notes)
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Additional Information</h4>
                            <div class="flex flex-wrap gap-2">
                                @if($booking->booth->size)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-ruler-combined mr-1"></i> {{ $booking->booth->size }}
                                </span>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex gap-3">
                            @if($booking->status === 'confirmed' || $booking->status === 'pending')
                            <button class="hover:cursor-pointer bg-red-50 hover:bg-red-100 text-red-600 font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                Request Refund
                            </button>
                            @endif

                            <a href="{{ route('my-booking-details', $booking->id) }}" class="hover:cursor-pointer bg-[#ff7700] hover:bg-[#e66600] text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 inline-block text-center">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <div class="max-w-md mx-auto">
                        <div class="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-inbox text-gray-400 text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No Bookings Yet</h3>
                        <p class="text-gray-600 mb-6">You haven't made any booth bookings yet. Start exploring events and book your booth today!</p>
                        <a href="{{ route('events') }}" class="inline-block bg-[#ff7700] hover:bg-[#e66600] text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200">
                            Browse Events
                        </a>
                    </div>
                </div>
                @endforelse
            </div>

        </div>
    </div>

    <!-- Footer -->
    @include('components.footer')
</body>

</html>
