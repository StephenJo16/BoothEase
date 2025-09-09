<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>All Events</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@php
// Events Data
$events = [
[
'name' => 'Tech Innovation Expo 2025',
'category' => 'Technology',
'categoryColor' => 'text-blue-600',
'gradientFrom' => 'from-blue-400',
'gradientTo' => 'to-blue-600',
'location' => 'Convention Center, Jakarta',
'date' => '15 December 2025',
'booths' => '50 Booths Available',
'price' => 'Rp 500,000',
'url' => '/event/details',
'buttonType' => 'link'
],
[
'name' => 'Green Tech Summit 2025',
'category' => 'Technology',
'categoryColor' => 'text-green-600',
'gradientFrom' => 'from-green-400',
'gradientTo' => 'to-green-600',
'location' => 'Convention Center, Jakarta',
'date' => '15 December 2025',
'booths' => '30 Booths Available',
'price' => 'Rp 750,000',
'url' => '#',
'buttonType' => 'button'
],
[
'name' => 'Digital Innovation Expo',
'category' => 'Technology',
'categoryColor' => 'text-purple-600',
'gradientFrom' => 'from-purple-400',
'gradientTo' => 'to-purple-600',
'location' => 'Convention Center, Jakarta',
'date' => '15 December 2025',
'booths' => '25 Booths Available',
'price' => 'Rp 600,000',
'url' => '#',
'buttonType' => 'button'
],
[
'name' => 'Food & Beverage Festival',
'category' => 'Food & Beverage',
'categoryColor' => 'text-red-600',
'gradientFrom' => 'from-red-400',
'gradientTo' => 'to-red-600',
'location' => 'Grand Mall, Surabaya',
'date' => '20 January 2026',
'booths' => '40 Booths Available',
'price' => 'Rp 400,000',
'url' => '#',
'buttonType' => 'button'
],
[
'name' => 'Startup Showcase 2025',
'category' => 'Business',
'categoryColor' => 'text-orange-600',
'gradientFrom' => 'from-yellow-400',
'gradientTo' => 'to-orange-500',
'location' => 'Tech Hub, Bandung',
'date' => '10 March 2026',
'booths' => '35 Booths Available',
'price' => 'Rp 300,000',
'url' => '#',
'buttonType' => 'button'
],
[
'name' => 'Fashion Week Indonesia',
'category' => 'Fashion',
'categoryColor' => 'text-indigo-600',
'gradientFrom' => 'from-indigo-400',
'gradientTo' => 'to-indigo-600',
'location' => 'Fashion Center, Jakarta',
'date' => '25 April 2026',
'booths' => '60 Booths Available',
'price' => 'Rp 800,000',
'url' => '#',
'buttonType' => 'button'
],
[
'name' => 'Wellness Retreat 2026',
'category' => 'Lifestyle',
'categoryColor' => 'text-pink-600',
'gradientFrom' => 'from-pink-400',
'gradientTo' => 'to-pink-600',
'location' => 'Resort & Spa, Bali',
'date' => '5 June 2026',
'booths' => '20 Booths Available',
'price' => 'Rp 1,200,000',
'url' => '#',
'buttonType' => 'button'
],
[
'name' => 'Indie Music Fest 2026',
'category' => 'Music',
'categoryColor' => 'text-teal-600',
'gradientFrom' => 'from-teal-400',
'gradientTo' => 'to-teal-600',
'location' => 'City Park, Yogyakarta',
'date' => '18 July 2026',
'booths' => '30 Booths Available',
'price' => 'Rp 350,000',
'url' => '#',
'buttonType' => 'button'
],
[
'name' => 'Art & Culture Fair',
'category' => 'Art & Culture',
'categoryColor' => 'text-cyan-600',
'gradientFrom' => 'from-cyan-400',
'gradientTo' => 'to-cyan-600',
'location' => 'National Gallery, Jakarta',
'date' => '22 August 2026',
'booths' => '45 Booths Available',
'price' => 'Rp 450,000',
'url' => '#',
'buttonType' => 'button'
],
[
'name' => 'Weekend Market',
'category' => 'Lifestyle',
'categoryColor' => 'text-red-600',
'gradientFrom' => 'from-red-400',
'gradientTo' => 'to-yellow-500',
'location' => 'City Square, Bandung',
'date' => '10 September 2026',
'booths' => '100 Booths Available',
'price' => 'Rp 200,000',
'url' => '#',
'buttonType' => 'button'
],
[
'name' => 'Education Fair 2026',
'category' => 'Education',
'categoryColor' => 'text-green-600',
'gradientFrom' => 'from-green-400',
'gradientTo' => 'to-blue-500',
'location' => 'University Hall, Surabaya',
'date' => '15 October 2026',
'booths' => '80 Booths Available',
'price' => 'Rp 250,000',
'url' => '#',
'buttonType' => 'button'
],
[
'name' => 'Comics & Hobbies Expo',
'category' => 'Hobbies',
'categoryColor' => 'text-purple-600',
'gradientFrom' => 'from-purple-400',
'gradientTo' => 'to-pink-500',
'location' => 'Expo Center, Jakarta',
'date' => '20 November 2026',
'booths' => '120 Booths Available',
'price' => 'Rp 300,000',
'url' => '#',
'buttonType' => 'button'
]
];
@endphp

<body class="bg-gray-50 min-h-screen">
    <!-- Navbar -->
    @include('components.navbar')

    <!-- Main Content -->
    <div class="min-h-screen">
        <!-- Header Section -->
        <section class="bg-white py-16 border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">All Events</h1>
                <p class="text-lg text-gray-600">Browse through our extensive list of events</p>
            </div>
        </section>

        <!-- Search and Filter Section -->
        <section class="py-8 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row gap-4 items-center justify-center">
                    <div class="w-full sm:w-96">
                        @include('components.search-bar', ['placeholder' => 'Search events...'])
                    </div>

                    <div class="w-full sm:w-auto">
                        @include('components.filter-button', ['label' => 'Filter'])
                    </div>
                </div>
            </div>
        </section>

        <!-- Events Grid -->
        <section class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($events as $event)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 relative">
                        <div class="h-48 bg-gradient-to-br {{ $event['gradientFrom'] }} {{ $event['gradientTo'] }} relative">
                            <span class="absolute top-3 right-3 bg-white bg-opacity-90 {{ $event['categoryColor'] }} text-xs font-semibold px-2 py-1 rounded-full">{{ $event['category'] }}</span>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $event['name'] }}</h3>
                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-[#ff7700]"></i>
                                    <span>{{ $event['location'] }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt mr-2 text-[#ff7700]"></i>
                                    <span>{{ $event['date'] }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-store mr-2 text-[#ff7700]"></i>
                                    <span>{{ $event['booths'] }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-tag mr-2 text-[#ff7700]"></i>
                                    <span>Starting from: {{ $event['price'] }}</span>
                                </div>
                            </div>
                            @if($event['buttonType'] === 'link')
                            <a href="{{ $event['url'] }}" class="block w-full bg-gray-100 hover:bg-[#ff7700] hover:text-white text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200 text-center">
                                View Details
                            </a>
                            @else
                            <button class="w-full bg-gray-100 hover:bg-[#ff7700] hover:text-white text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200 hover:cursor-pointer">
                                View Details
                            </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @include('components.pagination', ['showEllipsis' => true])
            </div>
        </section>
    </div>

    <!-- Footer -->
    @include('components.footer')
</body>

</html>