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
// Filter Tabs Data
$filterTabs = [
['name' => 'All Bookings', 'active' => true],
['name' => 'Approved', 'active' => false],
['name' => 'Rejected', 'active' => false],
['name' => 'Cancelled', 'active' => false]
];

// Bookings Data
$bookings = [
[
'eventName' => 'Tech Innovation Expo 2025',
'location' => 'Jakarta Convention Center',
'dates' => '20 - 28 September 2025',
'status' => 'Approved',
'statusColor' => 'bg-green-100 text-green-800',
'boothDetails' => 'Booth A01',
'bookingDate' => '18-10-2025',
'paymentMethod' => 'GoPay',
'bookingId' => 'ID-618261',
'features' => ['Power Outlet', 'WiFi', 'Storage Space', 'Display Wall']
],
[
'eventName' => 'Food & Beverage Festival',
'location' => 'Grand Mall, Surabaya',
'dates' => '15 - 18 December 2025',
'status' => 'Approved',
'statusColor' => 'bg-green-100 text-green-800',
'boothDetails' => 'Booth B12',
'bookingDate' => '22-10-2025',
'paymentMethod' => 'DANA',
'bookingId' => 'ID-618262',
'features' => ['Power Outlet', 'WiFi', 'Storage Space']
],
[
'eventName' => 'Fashion Week Indonesia',
'location' => 'Fashion Center, Jakarta',
'dates' => '10 - 15 January 2026',
'status' => 'Approved',
'statusColor' => 'bg-green-100 text-green-800',
'boothDetails' => 'Booth C05',
'bookingDate' => '25-10-2025',
'paymentMethod' => 'BCA Virtual Account',
'bookingId' => 'ID-618263',
'features' => ['Power Outlet', 'WiFi', 'Premium Location', 'Display Wall']
]
];
@endphp

<body class="bg-gray-50 min-h-screen">
    <!-- Navbar -->
    @include('components.navbar')

    <!-- Main Content -->
    <div class="min-h-screen">
        <!-- Header -->
        @include('components.header', ['title' => 'My Bookings', 'subtitle' => 'Manage Your Booth Reservations'])
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Bookings -->
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-left">
                            <p class="text-sm text-gray-600 mb-1">Total Bookings</p>
                            <p class="text-3xl font-bold text-gray-900">3</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-store text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Approved -->
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-left">
                            <p class="text-sm text-gray-600 mb-1">Approved</p>
                            <p class="text-3xl font-bold text-gray-900">3</p>
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
                            <p class="text-3xl font-bold text-gray-900">Rp1.500.000</p>
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
                @foreach($bookings as $index => $booking)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $booking['eventName'] }}</h3>
                                <div class="flex items-center mb-1">
                                    <i class="fas fa-map-marker-alt mr-2 text-[#ff7700]"></i>
                                    <p class="text-sm text-gray-600">{{ $booking['location'] }}</p>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt mr-2 text-[#ff7700]"></i>
                                    <p class="text-sm text-gray-600">{{ $booking['dates'] }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $booking['statusColor'] }}">
                                {{ $booking['status'] }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                            <!-- Booth Details -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Booth Details</h4>
                                <p class="text-sm text-gray-900 font-medium">{{ $booking['boothDetails'] }}</p>
                            </div>

                            <!-- Booking Date -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Booking Date</h4>
                                <p class="text-sm text-gray-900">{{ $booking['bookingDate'] }}</p>
                            </div>

                            <!-- Payment Method -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Payment Method</h4>
                                <p class="text-sm text-gray-900">{{ $booking['paymentMethod'] }}</p>
                            </div>

                            <!-- Booking ID -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Booking ID</h4>
                                <p class="text-sm text-gray-900">{{ $booking['bookingId'] }}</p>
                            </div>
                        </div>

                        <!-- Included Features -->
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Included Features</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($booking['features'] as $feature)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $feature }}
                                </span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3">
                            <button class="hover:cursor-pointer bg-red-50 hover:bg-red-100 text-red-600 font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                Request Refund
                            </button>
                            @if($index === 0)
                            <a href="{{ route('my-booking-details')}}" class="hover:cursor-pointer bg-[#ff7700] hover:bg-orange-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 inline-block text-center">
                                View Details
                            </a>
                            @else
                            <button class="hover:cursor-pointer bg-[#ff7700] hover:bg-orange-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                View Details
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @include('components.pagination')
        </div>
    </div>

    <!-- Footer -->
    @include('components.footer')
</body>

</html>