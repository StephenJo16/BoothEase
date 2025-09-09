<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Event List</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen">
    <!-- Navbar -->
    @include('components.navbar')

    <!-- Main Content -->
    <div class="min-h-screen">
        <!-- Header Section -->
        <section class="bg-white py-16 border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Top Events</h1>
                <p class="text-lg text-gray-600">Discover incredible events happening around you</p>
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
                    <!-- Event Card 1 -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 relative">
                        <div class="h-48 bg-gradient-to-br from-blue-400 to-blue-600 relative">
                            <span class="absolute top-3 right-3 bg-white bg-opacity-90 text-blue-600 text-xs font-semibold px-2 py-1 rounded-full">Technology</span>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Tech Innovation Expo 2025</h3>
                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-[#ff7700]"></i>
                                    <span>Location: Convention Center, Jakarta</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt mr-2 text-[#ff7700]"></i>
                                    <span>Date: 15 December 2025</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-store mr-2 text-[#ff7700]"></i>
                                    <span>50 Booths Available</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-tag mr-2 text-[#ff7700]"></i>
                                    <span>Starting from: Rp 500,000</span>
                                </div>
                            </div>
                            <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200 cursor-pointer">
                                View Details
                            </button>
                        </div>
                    </div>

                    <!-- Event Card 2 -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 relative">
                        <div class="h-48 bg-gradient-to-br from-green-400 to-green-600 relative">
                            <span class="absolute top-3 right-3 bg-white bg-opacity-90 text-green-600 text-xs font-semibold px-2 py-1 rounded-full">Technology</span>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Tech Innovation Expo 2025</h3>
                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-[#ff7700]"></i>
                                    <span>Location: Convention Center, Jakarta</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt mr-2 text-[#ff7700]"></i>
                                    <span>Date: 15 December 2025</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-store mr-2 text-[#ff7700]"></i>
                                    <span>30 Booths Available</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-tag mr-2 text-[#ff7700]"></i>
                                    <span>Starting from: Rp 750,000</span>
                                </div>
                            </div>
                            <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200 cursor-pointer">
                                View Details
                            </button>
                        </div>
                    </div>

                    <!-- Event Card 3 -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 relative">
                        <div class="h-48 bg-gradient-to-br from-purple-400 to-purple-600 relative">
                            <span class="absolute top-3 right-3 bg-white bg-opacity-90 text-purple-600 text-xs font-semibold px-2 py-1 rounded-full">Technology</span>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Tech Innovation Expo 2025</h3>
                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-[#ff7700]"></i>
                                    <span>Location: Convention Center, Jakarta</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt mr-2 text-[#ff7700]"></i>
                                    <span>Date: 15 December 2025</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-store mr-2 text-[#ff7700]"></i>
                                    <span>25 Booths Available</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-tag mr-2 text-[#ff7700]"></i>
                                    <span>Starting from: Rp 600,000</span>
                                </div>
                            </div>
                            <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200 cursor-pointer">
                                View Details
                            </button>
                        </div>
                    </div>

                    <!-- Event Card 4 -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 relative">
                        <div class="h-48 bg-gradient-to-br from-red-400 to-red-600 relative">
                            <span class="absolute top-3 right-3 bg-white bg-opacity-90 text-red-600 text-xs font-semibold px-2 py-1 rounded-full">Food & Beverage</span>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Food & Beverage Festival</h3>
                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-[#ff7700]"></i>
                                    <span>Location: Grand Mall, Surabaya</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt mr-2 text-[#ff7700]"></i>
                                    <span>Date: 20 January 2026</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-store mr-2 text-[#ff7700]"></i>
                                    <span>40 Booths Available</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-tag mr-2 text-[#ff7700]"></i>
                                    <span>Starting from: Rp 400,000</span>
                                </div>
                            </div>
                            <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200 cursor-pointer">
                                View Details
                            </button>
                        </div>
                    </div>

                    <!-- Event Card 5 -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 relative">
                        <div class="h-48 bg-gradient-to-br from-yellow-400 to-orange-500 relative">
                            <span class="absolute top-3 right-3 bg-white bg-opacity-90 text-orange-600 text-xs font-semibold px-2 py-1 rounded-full">Business</span>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Startup Showcase 2025</h3>
                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-[#ff7700]"></i>
                                    <span>Location: Tech Hub, Bandung</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt mr-2 text-[#ff7700]"></i>
                                    <span>Date: 10 March 2026</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-store mr-2 text-[#ff7700]"></i>
                                    <span>35 Booths Available</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-tag mr-2 text-[#ff7700]"></i>
                                    <span>Starting from: Rp 300,000</span>
                                </div>
                            </div>
                            <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200 cursor-pointer">
                                View Details
                            </button>
                        </div>
                    </div>

                    <!-- Event Card 6 -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 relative">
                        <div class="h-48 bg-gradient-to-br from-indigo-400 to-indigo-600 relative">
                            <span class="absolute top-3 right-3 bg-white bg-opacity-90 text-indigo-600 text-xs font-semibold px-2 py-1 rounded-full">Fashion</span>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Fashion Week Indonesia</h3>
                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-[#ff7700]"></i>
                                    <span>Location: Fashion Center, Jakarta</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt mr-2 text-[#ff7700]"></i>
                                    <span>Date: 25 April 2026</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-store mr-2 text-[#ff7700]"></i>
                                    <span>60 Booths Available</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-tag mr-2 text-[#ff7700]"></i>
                                    <span>Starting from: Rp 800,000</span>
                                </div>
                            </div>
                            <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200 cursor-pointer">
                                View Details
                            </button>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-12">
                    <button class="bg-gray-800 hover:bg-gray-900 text-white font-semibold px-8 py-3 rounded-lg transition-colors duration-200 hover:cursor-pointer">
                        View All Events
                    </button>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex justify-center items-center mb-6">
                <img src="{{ asset('images/boothease-logo-cropped.png') }}" alt="BoothEase" class="h-10 mr-3">
            </div>
            <p class="text-gray-400 mb-4">Making event booth booking simple and efficient</p>
            <p class="text-sm text-gray-500">All Rights Reserved ©</p>
        </div>
    </footer>
</body>

</html>