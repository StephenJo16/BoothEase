<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BoothEase - Event Booth Booking Made Easy</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@php
// Color schemes for different event cards
$colorSchemes = [
[
'categoryColor' => 'text-blue-600',
'gradientFrom' => 'from-blue-400',
'gradientTo' => 'to-blue-600',
],
[
'categoryColor' => 'text-green-600',
'gradientFrom' => 'from-green-400',
'gradientTo' => 'to-green-600',
],
[
'categoryColor' => 'text-purple-600',
'gradientFrom' => 'from-purple-400',
'gradientTo' => 'to-purple-600',
],
];
@endphp

<body class="bg-gray-50">
    <!-- Navbar -->
    @include('components.navbar')

    <!-- Hero Section -->
    <section class="bg-white py-40 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-12 items-center">
                <!-- Left Content -->
                <div class="mb-12 lg:mb-0">
                    <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                        Looking for an event?
                    </h1>
                    <p class="text-xl text-gray-600 mb-8">
                        Discover and book the perfect booth in just a few clicks.
                    </p>
                    <a href="/signup" class="inline-block bg-[#ff7700] hover:bg-[#e66600] text-white font-semibold px-8 py-3 rounded-lg transition-colors">
                        Get Started!
                    </a>
                </div>

                <!-- Right Content - Logo -->
                <div class="flex justify-center lg:justify-end">
                    <div class="w-140 h-80 rounded-lg flex items-center justify-center">
                        <img src="{{ asset('images/boothease-logo-cropped.png') }}" alt="BoothEase Logo" class="max-w-full max-h-full object-contain">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why BoothEase Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Why BoothEase?</h2>
                <p class="text-lg text-gray-600">The complete solution for modern event management</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                <!-- Secure -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-[#ff7700] bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-shield-halved text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Secure</h3>
                    <p class="text-gray-600">Your data and transactions are protected with enterprise-grade security</p>
                </div>

                <!-- Easy to Use -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-[#ff7700] bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-check text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Easy to Use</h3>
                    <p class="text-gray-600">Intuitive interface designed for seamless event management</p>
                </div>

                <!-- Increase Revenue -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-[#ff7700] bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-chart-simple text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Increase Revenue</h3>
                    <p class="text-gray-600">Optimize booth allocation and maximize your event profitability</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Events Grid -->
    <section class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl lg:text-2xl font-bold text-gray-900 mb-8 text-center">Top Events Right Now</h2>
            @if($topEvents->isEmpty())
            <div class="text-center py-12">
                <p class="text-gray-600 text-lg">No published events available at the moment.</p>
            </div>
            @else
            <div class="flex justify-center">
                <div class="flex flex-wrap justify-center gap-8">
                    @foreach($topEvents as $index => $event)
                    @php
                    $scheme = $colorSchemes[$index % count($colorSchemes)];
                    $availableBooths = $event->booths->where('status', 'available')->count();
                    $minPrice = $event->booths->min('price');
                    @endphp
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 relative w-full md:w-80">
                        <div class="h-48 bg-gradient-to-br {{ $scheme['gradientFrom'] }} {{ $scheme['gradientTo'] }} relative">
                            <span class="absolute top-3 right-3 bg-white bg-opacity-90 {{ $scheme['categoryColor'] }} text-xs font-semibold px-2 py-1 rounded-full">
                                {{ $event->category->name ?? 'General' }}
                            </span>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $event->title }}</h3>
                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-[#ff7700]"></i>
                                    <span>{{ $event->display_location ?? 'Location TBA' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt mr-2 text-[#ff7700]"></i>
                                    <span>{{ $event->start_time->format('d F Y') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-store mr-2 text-[#ff7700]"></i>
                                    <span>{{ $availableBooths }} {{ $availableBooths === 1 ? 'Booth' : 'Booths' }} Available</span>
                                </div>
                                @if($minPrice)
                                <div class="flex items-center">
                                    <i class="fas fa-tag mr-2 text-[#ff7700]"></i>
                                    <span>Starting from: {{ formatRupiah($minPrice) }}</span>
                                </div>
                                @endif
                            </div>
                            <a href="{{ route('login') }}" class="block w-full bg-gray-100 hover:bg-[#ff7700] hover:text-white text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200 text-center">
                                View Details
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-40 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 lg:gap-20">
                <!-- For Tenants -->
                <div class="text-center md:text-left">
                    <h2 class="text-3xl font-bold text-[#ff7700] mb-6">For Tenants</h2>
                    <p class="text-lg text-gray-700 leading-relaxed">
                        Find events that match your brand. Browse, book, and manage your booths easily.
                    </p>
                </div>

                <!-- For Event Organizers -->
                <div class="text-center md:text-left">
                    <h2 class="text-3xl font-bold text-[#ff7700] mb-6">For Event Organizers</h2>
                    <p class="text-lg text-gray-700 leading-relaxed">
                        Host events, upload layouts, manage tenants, and track bookings - all in one place.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    @include('components.footer')
</body>

</html>