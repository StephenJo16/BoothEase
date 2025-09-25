<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Events</title>

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
// strip non-digits
$digits = preg_replace('/\D/', '', (string) $value);
$num = $digits === '' ? 0 : intval($digits);
return 'Rp' . number_format($num, 0, ',', '.');
}
}

// My Events Data
$myEvents = [
[
'name' => 'Tech Innovation Expo 2025',
'category' => 'Technology',
'status' => 'Active',
'statusColor' => 'bg-green-100 text-green-800',
'location' => 'Jakarta Convention Center',
'startDate' => '16 Nov 2025',
'endDate' => '20 Nov 2025',
'bookedBooths' => 45,
'totalBooths' => 100,
'revenue' => 19845000,
'bookingRate' => 45
],
[
'name' => 'Digital Marketing Summit',
'category' => 'Marketing',
'status' => 'Active',
'statusColor' => 'bg-green-100 text-green-800',
'location' => 'Bali International Convention Centre',
'startDate' => '5 Dec 2025',
'endDate' => '7 Dec 2025',
'bookedBooths' => 28,
'totalBooths' => 50,
'revenue' => 14000000,
'bookingRate' => 56
],
[
'name' => 'Food & Beverage Expo',
'category' => 'Food & Beverage',
'status' => 'Draft',
'statusColor' => 'bg-yellow-100 text-yellow-800',
'location' => 'Surabaya Convention Hall',
'startDate' => '15 Jan 2026',
'endDate' => '18 Jan 2026',
'bookedBooths' => 0,
'totalBooths' => 75,
'revenue' => 0,
'bookingRate' => 0
],
[
'name' => 'Fashion Week Indonesia',
'category' => 'Fashion',
'status' => 'Completed',
'statusColor' => 'bg-gray-100 text-gray-800',
'location' => 'Fashion Center, Jakarta',
'startDate' => '10 Sep 2024',
'endDate' => '15 Sep 2024',
'bookedBooths' => 60,
'totalBooths' => 60,
'revenue' => 48000000,
'bookingRate' => 100
],
[
'name' => 'Startup Showcase 2024',
'category' => 'Business',
'status' => 'Completed',
'statusColor' => 'bg-gray-100 text-gray-800',
'location' => 'Tech Hub, Bandung',
'startDate' => '20 Aug 2024',
'endDate' => '22 Aug 2024',
'bookedBooths' => 35,
'totalBooths' => 40,
'revenue' => 10500000,
'bookingRate' => 87
],
[
'name' => 'Green Energy Conference',
'category' => 'Technology',
'status' => 'Cancelled',
'statusColor' => 'bg-red-100 text-red-800',
'location' => 'Convention Center, Jakarta',
'startDate' => '25 Jul 2024',
'endDate' => '27 Jul 2024',
'bookedBooths' => 12,
'totalBooths' => 80,
'revenue' => 6000000,
'bookingRate' => 15
]
];
@endphp

<body class="bg-gray-50 min-h-screen">
    <!-- Navbar -->
    @include('components.navbar')

    <!-- Main Content -->
    <div class="min-h-screen">
        <!-- Header Section -->
        @include('components.header', ['title' => 'My Events', 'subtitle' => 'Manage your events'])

        <!-- Search, Filter, and Create New Event Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between mb-8">
                <div class="flex w-full md:w-auto gap-2 flex-1">
                    <div class="w-full md:w-96">
                        @include('components.search-bar', ['placeholder' => 'Search events...'])
                    </div>
                    <div class="w-full md:w-auto">
                        @include('components.filter-button', ['label' => 'Filter'])
                    </div>
                </div>
                <a href="{{ route('create-event') }}" class="bg-[#ff7700] hover:bg-[#e66600] hover:cursor-pointer text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Create New Event
                </a>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Events Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($myEvents as $index => $event)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <!-- Event Header -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-lg font-semibold text-gray-900 leading-tight">{{ $event['name'] }}</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $event['statusColor'] }}">
                                {{ $event['status'] }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-1">{{ $event['category'] }}</p>
                    </div>

                    <!-- Event Details -->
                    <div class="p-6">
                        <div class="space-y-3 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-map-marker-alt mr-2 text-[#ff7700] w-4"></i>
                                <span>{{ $event['location'] }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-calendar-alt mr-2 text-[#ff7700] w-4"></i>
                                <span>{{ $event['startDate'] }} - {{ $event['endDate'] }}</span>
                            </div>
                        </div>

                        <!-- Statistics -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Booked Booths</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ $event['bookedBooths'] }}/{{ $event['totalBooths'] }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Revenue</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ formatRupiah($event['revenue']) }}</p>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mt-3">
                                <div class="flex justify-between text-xs text-gray-500 mb-1">
                                    <span>Booking Progress</span>
                                    <span>{{ $event['bookingRate'] }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="progress-fill bg-[#ff7700] h-2 rounded-full transition-all duration-300" data-rate="{{ $event['bookingRate'] }}"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            @if($event['status'] === 'Draft')
                            <button class="flex-1 bg-[#ff7700] hover:bg-[#e66600] hover:cursor-pointer text-white text-sm font-medium py-2 px-3 rounded-lg transition-colors duration-200">
                                Publish
                            </button>
                            <button
                                onclick="window.location.href='/my-events/edit';"
                                class="flex-1 bg-gray-100 hover:bg-gray-200 hover:cursor-pointer text-gray-800 text-sm font-medium py-2 px-3 rounded-lg transition-colors duration-200">
                                Edit
                            </button>
                            @elseif($event['status'] === 'Active')
                            @if($index === 0)
                            <a href="{{ url('/my-events/details') }}" class="flex-1 bg-[#ff7700] hover:bg-[#e66600] hover:cursor-pointer text-white text-sm font-medium py-2 px-3 rounded-lg transition-colors duration-200 inline-block text-center">
                                View
                            </a>
                            @else
                            <button class="flex-1 bg-[#ff7700] hover:bg-[#e66600] hover:cursor-pointer text-white text-sm font-medium py-2 px-3 rounded-lg transition-colors duration-200">
                                View
                            </button>
                            @endif
                            @elseif($event['status'] === 'Completed')
                            <button class="flex-1 bg-gray-100 hover:bg-gray-200 hover:cursor-pointer text-gray-800 text-sm font-medium py-2 px-3 rounded-lg transition-colors duration-200">
                                View Report
                            </button>
                            <button class="flex-1 bg-gray-100 hover:bg-gray-200 hover:cursor-pointer text-gray-800 text-sm font-medium py-2 px-3 rounded-lg transition-colors duration-200">
                                Archive
                            </button>
                            @else
                            <button class="flex-1 bg-gray-100 hover:bg-gray-200 hover:cursor-pointer text-gray-800 text-sm font-medium py-2 px-3 rounded-lg transition-colors duration-200">
                                View Details
                            </button>
                            <button class="flex-1 bg-red-100 hover:bg-red-200 hover:cursor-pointer text-red-800 text-sm font-medium py-2 px-3 rounded-lg transition-colors duration-200">
                                Delete
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @include('components.pagination', ['showEllipsis' => false])
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-8">
        @include('components.footer')
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.progress-fill').forEach(function(el) {
                var rate = parseFloat(el.getAttribute('data-rate'));
                if (!isNaN(rate)) {
                    el.style.width = rate + '%';
                }
            });
        });
    </script>
</body>

</html>