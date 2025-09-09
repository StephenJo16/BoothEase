<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tech Innovation Expo 2025 - Event Details</title>

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
    <div class="min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                @include('components.back-button', ['url' => '/events', 'text' => 'Back to Events'])
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                <!-- Left Column - Event Info -->
                <div class="lg:col-span-3">
                    <!-- Event Header -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                        <div class="h-64 bg-gradient-to-br from-blue-400 to-blue-600 relative">
                            <span class="absolute top-4 right-4 bg-white bg-opacity-90 text-blue-600 text-sm font-semibold px-3 py-1 rounded-full">Technology</span>
                            <!-- <div class="absolute top-4 right-4 mt-10 flex gap-2">
                                <button class="bg-white bg-opacity-90 text-gray-700 p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <button class="bg-white bg-opacity-90 text-gray-700 p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                    <i class="fas fa-share"></i>
                                </button>
                            </div> -->
                        </div>
                        <div class="p-6">
                            <h1 class="text-3xl font-bold text-gray-900 mb-4">Tech Innovation Expo 2025</h1>
                            <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-6">
                                <div class="flex items-center">
                                    <i class="fas fa-star mr-2 text-[#ff7700]"></i>
                                    <span>5.0 (50,147 Reviews)</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-store mr-2 text-[#ff7700]"></i>
                                    <span>89 / 100 Booths Available</span>
                                </div>
                            </div>
                            <p class="text-gray-700 leading-relaxed">
                                Join us for the biggest technology innovation expo of the year. Discover cutting-edge technologies, network with industry leaders, and showcase your products to thousands of potential customers. This premier event brings together startups, enterprises, and tech enthusiasts for three days of innovation, networking, and discovery.
                            </p>
                        </div>
                    </div>

                    <!-- Event Details Tabs -->
                    <div class=" bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="border-b border-gray-200">
                            <nav class="flex">
                                <button class="hover:cursor-pointer bg-[#ff7700] text-white px-6 py-3 font-medium border-b-2 border-[#ff7700]">
                                    Booths
                                </button>
                                <button class="hover:cursor-pointer text-gray-600 hover:text-gray-800 px-6 py-3 font-medium border-b-2 border-transparent hover:border-gray-300 transition-colors duration-200">
                                    Details
                                </button>
                                <button class="hover:cursor-pointer text-gray-600 hover:text-gray-800 px-6 py-3 font-medium border-b-2 border-transparent hover:border-gray-300 transition-colors duration-200">
                                    Schedule
                                </button>
                                <button class="hover:cursor-pointer text-gray-600 hover:text-gray-800 px-6 py-3 font-medium border-b-2 border-transparent hover:border-gray-300 transition-colors duration-200">
                                    Organizer
                                </button>
                                <button class="text-gray-600 hover:text-gray-800 px-6 py-3 font-medium border-b-2 border-transparent hover:border-gray-300 transition-colors duration-200">
                                    Reviews
                                </button>
                            </nav>
                        </div>

                        <!-- Available Booths Content -->
                        <div class="p-6">
                            <div class="mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Available Booths</h3>
                                <p class="text-sm text-gray-600">Select a booth that fits your needs</p>
                            </div>

                            <!-- Booth Table -->
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="border-b border-gray-200">
                                            <th class="text-left py-3 px-4 font-medium text-gray-700">Booth Number</th>
                                            <th class="text-left py-3 px-4 font-medium text-gray-700">Size</th>
                                            <th class="text-left py-3 px-4 font-medium text-gray-700">Location</th>
                                            <th class="text-left py-3 px-4 font-medium text-gray-700">Price</th>
                                            <th class="text-left py-3 px-4 font-medium text-gray-700">Status</th>
                                            <th class="text-left py-3 px-4 font-medium text-gray-700">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr>
                                            <td class="py-3 px-4 font-medium text-gray-900">A01</td>
                                            <td class="py-3 px-4 text-gray-600">5x4m</td>
                                            <td class="py-3 px-4 text-gray-600">Main Hall - Front</td>
                                            <td class="py-3 px-4 text-gray-900 font-medium">Rp500,000</td>
                                            <td class="py-3 px-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Booked
                                                </span>
                                            </td>
                                            <td class="py-3 px-4">
                                                <span class="text-gray-400 text-sm">Not Available</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="py-3 px-4 font-medium text-gray-900">A02</td>
                                            <td class="py-3 px-4 text-gray-600">5x4m</td>
                                            <td class="py-3 px-4 text-gray-600">Main Hall - Front</td>
                                            <td class="py-3 px-4 text-gray-900 font-medium">Rp500,000</td>
                                            <td class="py-3 px-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Available
                                                </span>
                                            </td>
                                            <td class="py-3 px-4">
                                                <button class="bg-[#ff7700] hover:bg-orange-600 hover:cursor-pointer text-white text-sm px-4 py-1 rounded-lg transition-colors duration-200">
                                                    Select
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="py-3 px-4 font-medium text-gray-900">A03</td>
                                            <td class="py-3 px-4 text-gray-600">3x3m</td>
                                            <td class="py-3 px-4 text-gray-600">Main Hall - Middle</td>
                                            <td class="py-3 px-4 text-gray-900 font-medium">Rp350,000</td>
                                            <td class="py-3 px-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Available
                                                </span>
                                            </td>
                                            <td class="py-3 px-4">
                                                <button class="bg-[#ff7700] hover:bg-orange-600 hover:cursor-pointer text-white text-sm px-4 py-1 rounded-lg transition-colors duration-200">
                                                    Select
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="py-3 px-4 font-medium text-gray-900">B01</td>
                                            <td class="py-3 px-4 text-gray-600">4x4m</td>
                                            <td class="py-3 px-4 text-gray-600">Side Hall - Premium</td>
                                            <td class="py-3 px-4 text-gray-900 font-medium">Rp750,000</td>
                                            <td class="py-3 px-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Available
                                                </span>
                                            </td>
                                            <td class="py-3 px-4">
                                                <button class="bg-[#ff7700] hover:bg-orange-600 hover:cursor-pointer text-white text-sm px-4 py-1 rounded-lg transition-colors duration-200">
                                                    Select
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Booking Card -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md p-6  top-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Book Your Booth</h2>

                        <div class="space-y-4">
                            <div class="text-sm text-gray-600">
                                <span>Starting from:</span>
                                <span class="text-xl font-bold text-gray-900 ml-1">Rp 500,000 - 1,000,000</span>
                            </div>

                            <!-- Date Selection -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                    <div class="relative">
                                        <input type="text" value="16 November 2025" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" readonly>
                                        <i class="fas fa-calendar-alt absolute right-1.5 top-2.5 text-gray-400"></i>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">08:00</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                                    <div class="relative">
                                        <input type="text" value="20 November 2025" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" readonly>
                                        <i class="fas fa-calendar-alt absolute right-1.5 top-2.5 text-gray-400"></i>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">17:00</div>
                                </div>
                            </div>

                            <!-- Location -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-2 text-[#ff7700]"></i>
                                    <span>Jakarta Convention Center</span>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">Jl. Gatot Subroto No.1, Jakarta Selatan, DKI Jakarta 12930</div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="space-y-2 pt-4">
                                <button class="w-full hover:cursor-pointer bg-[#ff7700] hover:bg-orange-600 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                                    Contact Organizer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('components.footer')
</body>

</html>