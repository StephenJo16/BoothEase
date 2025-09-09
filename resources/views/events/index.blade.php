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
                            <a href="/event/details" class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200 text-center">
                                View Details
                            </a>
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

                    <!-- Event Card 7 -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 relative">
                        <div class="h-48 bg-gradient-to-br from-pink-400 to-pink-600 relative">
                            <span class="absolute top-3 right-3 bg-white bg-opacity-90 text-pink-600 text-xs font-semibold px-2 py-1 rounded-full">Lifestyle</span>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Wellness Retreat 2026</h3>
                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-[#ff7700]"></i>
                                    <span>Location: Resort & Spa, Bali</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt mr-2 text-[#ff7700]"></i>
                                    <span>Date: 5 June 2026</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-store mr-2 text-[#ff7700]"></i>
                                    <span>20 Booths Available</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-tag mr-2 text-[#ff7700]"></i>
                                    <span>Starting from: Rp 1,200,000</span>
                                </div>
                            </div>
                            <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200 cursor-pointer">
                                View Details
                            </button>
                        </div>
                    </div>

                    <!-- Event Card 8 -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 relative">
                        <div class="h-48 bg-gradient-to-br from-teal-400 to-teal-600 relative">
                            <span class="absolute top-3 right-3 bg-white bg-opacity-90 text-teal-600 text-xs font-semibold px-2 py-1 rounded-full">Music</span>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Indie Music Fest 2026</h3>
                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-[#ff7700]"></i>
                                    <span>Location: City Park, Yogyakarta</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt mr-2 text-[#ff7700]"></i>
                                    <span>Date: 18 July 2026</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-store mr-2 text-[#ff7700]"></i>
                                    <span>30 Booths Available</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-tag mr-2 text-[#ff7700]"></i>
                                    <span>Starting from: Rp 350,000</span>
                                </div>
                            </div>
                            <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200 cursor-pointer">
                                View Details
                            </button>
                        </div>
                    </div>

                    <!-- Event Card 9 -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 relative">
                        <div class="h-48 bg-gradient-to-br from-cyan-400 to-cyan-600 relative">
                            <span class="absolute top-3 right-3 bg-white bg-opacity-90 text-cyan-600 text-xs font-semibold px-2 py-1 rounded-full">Art & Culture</span>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Art & Culture Fair</h3>
                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-[#ff7700]"></i>
                                    <span>Location: National Gallery, Jakarta</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt mr-2 text-[#ff7700]"></i>
                                    <span>Date: 22 August 2026</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-store mr-2 text-[#ff7700]"></i>
                                    <span>45 Booths Available</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-tag mr-2 text-[#ff7700]"></i>
                                    <span>Starting from: Rp 450,000</span>
                                </div>
                            </div>
                            <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200 cursor-pointer">
                                View Details
                            </button>
                        </div>
                    </div>

                    <!-- Event Card 10 -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 relative">
                        <div class="h-48 bg-gradient-to-br from-red-400 to-yellow-500 relative">
                            <span class="absolute top-3 right-3 bg-white bg-opacity-90 text-red-600 text-xs font-semibold px-2 py-1 rounded-full">Lifestyle</span>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Weekend Market</h3>
                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-[#ff7700]"></i>
                                    <span>Location: City Square, Bandung</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt mr-2 text-[#ff7700]"></i>
                                    <span>Date: 10 September 2026</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-store mr-2 text-[#ff7700]"></i>
                                    <span>100 Booths Available</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-tag mr-2 text-[#ff7700]"></i>
                                    <span>Starting from: Rp 200,000</span>
                                </div>
                            </div>
                            <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200 cursor-pointer">
                                View Details
                            </button>
                        </div>
                    </div>

                    <!-- Event Card 11 -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 relative">
                        <div class="h-48 bg-gradient-to-br from-green-400 to-blue-500 relative">
                            <span class="absolute top-3 right-3 bg-white bg-opacity-90 text-green-600 text-xs font-semibold px-2 py-1 rounded-full">Education</span>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Education Fair 2026</h3>
                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-[#ff7700]"></i>
                                    <span>Location: University Hall, Surabaya</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt mr-2 text-[#ff7700]"></i>
                                    <span>Date: 15 October 2026</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-store mr-2 text-[#ff7700]"></i>
                                    <span>80 Booths Available</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-tag mr-2 text-[#ff7700]"></i>
                                    <span>Starting from: Rp 250,000</span>
                                </div>
                            </div>
                            <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200 cursor-pointer">
                                View Details
                            </button>
                        </div>
                    </div>

                    <!-- Event Card 12 -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 relative">
                        <div class="h-48 bg-gradient-to-br from-purple-400 to-pink-500 relative">
                            <span class="absolute top-3 right-3 bg-white bg-opacity-90 text-purple-600 text-xs font-semibold px-2 py-1 rounded-full">Hobbies</span>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Comics & Hobbies Expo</h3>
                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-[#ff7700]"></i>
                                    <span>Location: Expo Center, Jakarta</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt mr-2 text-[#ff7700]"></i>
                                    <span>Date: 20 November 2026</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-store mr-2 text-[#ff7700]"></i>
                                    <span>120 Booths Available</span>
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




                </div>

                <!-- Pagination -->
                <div class="mt-12 flex justify-center">
                    <nav class="relative z-0 inline-flex rounded-lg shadow-md bg-white overflow-hidden" aria-label="Pagination">
                        <a href="#" class="relative inline-flex items-center px-3 py-2 border border-gray-200 text-sm font-medium text-gray-600 hover:bg-[#ff7700] hover:text-white transition-colors duration-200">
                            <span class="sr-only">Previous</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#" aria-current="page" class="z-10 bg-[#ff7700] border-[#ff7700] text-white relative inline-flex items-center px-4 py-2 border text-sm font-medium shadow-sm"> 1 </a>
                        <a href="#" class="bg-white border-gray-200 text-gray-600 hover:bg-[#ff7700] hover:text-white relative inline-flex items-center px-4 py-2 border text-sm font-medium transition-colors duration-200"> 2 </a>
                        <a href="#" class="bg-white border-gray-200 text-gray-600 hover:bg-[#ff7700] hover:text-white hidden md:inline-flex relative items-center px-4 py-2 border text-sm font-medium transition-colors duration-200"> 3 </a>
                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-200 bg-gray-50 text-sm font-medium text-gray-500"> ... </span>
                        <a href="#" class="bg-white border-gray-200 text-gray-600 hover:bg-[#ff7700] hover:text-white hidden md:inline-flex relative items-center px-4 py-2 border text-sm font-medium transition-colors duration-200"> 8 </a>
                        <a href="#" class="bg-white border-gray-200 text-gray-600 hover:bg-[#ff7700] hover:text-white relative inline-flex items-center px-4 py-2 border text-sm font-medium transition-colors duration-200"> 9 </a>
                        <a href="#" class="bg-white border-gray-200 text-gray-600 hover:bg-[#ff7700] hover:text-white relative inline-flex items-center px-4 py-2 border text-sm font-medium transition-colors duration-200"> 10 </a>
                        <a href="#" class="relative inline-flex items-center px-3 py-2 border border-gray-200 text-sm font-medium text-gray-600 hover:bg-[#ff7700] hover:text-white transition-colors duration-200">
                            <span class="sr-only">Next</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </nav>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    @include('components.footer')
</body>

</html>