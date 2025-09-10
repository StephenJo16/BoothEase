<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tech Innovation Expo 2025 - BoothEase</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen font-['Instrument_Sans']">
    <!-- Navbar -->
    @include('components.navbar')

    @php
    $bookingRequests = [
    ['booth' => 'A01', 'business' => 'Pegasus Peripherals', 'requestedOn' => '15 Oct 2025', 'amount' => 'Rp1,200,000', 'status' => 'Pending'],
    ['booth' => 'B05', 'business' => 'BallYards', 'requestedOn' => '12 Oct 2025', 'amount' => 'Rp1,200,000', 'status' => 'Pending'],
    ['booth' => 'A06', 'business' => 'HealthyGo', 'requestedOn' => '10 Oct 2025', 'amount' => 'Rp1,200,000', 'status' => 'Pending'],
    ];
    @endphp

    <!-- Main Content -->
    <div class="min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('components.back-button', ['url' => '/my-events', 'text' => 'Back to My Events'])
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Tech Innovation Expo 2025</h1>
                        <p class="text-gray-600">Technology • 16 - 20 November 2025</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button class="bg-[#ff7700] hover:bg-orange-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Event
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 mb-8">
                <!-- Total Revenue -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Total Revenue</p>
                            <p class="text-2xl font-bold text-gray-900">Rp25,500,000</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Booked Booths -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Booked Booths</p>
                            <p class="text-2xl font-bold text-gray-900">45/100</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-store text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Booking Rate -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Booking Rate</p>
                            <p class="text-2xl font-bold text-gray-900">45%</p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-full">
                            <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Event Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Event Information</h2>
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-1">Location</h4>
                                    <p class="text-gray-900">Tech Convention Center, Jakarta</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-1">Category</h4>
                                    <p class="text-gray-900">Technology</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-1">Start Date</h4>
                                    <p class="text-gray-900">16 November 2025</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-1">End Date</h4>
                                    <p class="text-gray-900">20 November 2025</p>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Description</h4>
                                <p class="text-gray-600 leading-relaxed">
                                    Join us for the Tech Innovation Expo 2025, where the latest in technology and innovation will be showcased. This event gathers tech enthusiasts, industry leaders, and media for an insightful and engaging experience.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Booth Layout -->
                    {{--
                    <!-- Booth Layout -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Booth Layout Overview</h2>
                        <div class="bg-gray-100 rounded-lg p-8">
                            <div class="grid grid-cols-6 gap-2 max-w-md mx-auto">
                                <!-- Stage Area -->
                                <div class="col-span-6 bg-purple-200 text-purple-800 text-xs font-medium py-2 px-3 rounded text-center mb-4">
                                    Main Runway Stage
                                </div>

                                <!-- Booth Grid -->
                                <div class="bg-green-200 text-green-800 text-xs font-semibold p-2 rounded text-center">A01</div>
                                <div class="bg-green-200 text-green-800 text-xs font-semibold p-2 rounded text-center">A02</div>
                                <div class="bg-green-200 text-green-800 text-xs font-semibold p-2 rounded text-center">A03</div>
                                <div class="bg-green-200 text-green-800 text-xs font-semibold p-2 rounded text-center">A04</div>
                                <div class="bg-green-200 text-green-800 text-xs font-semibold p-2 rounded text-center">A05</div>
                                <div class="bg-green-200 text-green-800 text-xs font-semibold p-2 rounded text-center">A06</div>

                                <div class="bg-green-200 text-green-800 text-xs font-semibold p-2 rounded text-center">B01</div>
                                <div class="bg-green-200 text-green-800 text-xs font-semibold p-2 rounded text-center">B02</div>
                                <div class="bg-green-200 text-green-800 text-xs font-semibold p-2 rounded text-center">B03</div>
                                <div class="bg-green-200 text-green-800 text-xs font-semibold p-2 rounded text-center">B04</div>
                                <div class="bg-green-200 text-green-800 text-xs font-semibold p-2 rounded text-center">B05</div>
                                <div class="bg-green-200 text-green-800 text-xs font-semibold p-2 rounded text-center">B06</div>
                            </div>
                            <div class="flex justify-center gap-4 mt-4 text-xs">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-200 rounded mr-1"></div>
                                    <span class="text-gray-600">Booked (45)</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-gray-200 rounded mr-1"></div>
                                    <span class="text-gray-600">Available (55)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    --}}

                    <!-- Booking Requests -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold text-gray-900">Booking Requests</h2>
                            <button class="text-[#ff7700] hover:text-orange-600 text-sm font-medium">View All</button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left text-sm font-medium text-gray-700 pb-3">Booth</th>
                                        <th class="text-left text-sm font-medium text-gray-700 pb-3">Business Name</th>
                                        <th class="text-left text-sm font-medium text-gray-700 pb-3">Requested On</th>
                                        <th class="text-left text-sm font-medium text-gray-700 pb-3">Amount</th>
                                        <th class="text-left text-sm font-medium text-gray-700 pb-3">Status</th>
                                        <th class="text-left text-sm font-medium text-gray-700 pb-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($bookingRequests as $request)
                                    <tr>
                                        <td class="py-3 text-sm font-medium text-gray-900">{{ $request['booth'] }}</td>
                                        <td class="py-3 text-sm text-gray-600">{{ $request['business'] }}</td>
                                        <td class="py-3 text-sm text-gray-600">{{ $request['requestedOn'] }}</td>
                                        <td class="py-3 text-sm text-gray-900">{{ $request['amount'] }}</td>
                                        <td class="py-3">
                                            @if($request['status'] === 'Pending')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">{{ $request['status'] }}</span>
                                            @elseif($request['status'] === 'Approved')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ $request['status'] }}</span>
                                            @elseif($request['status'] === 'Rejected')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ $request['status'] }}</span>
                                            @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $request['status'] }}</span>
                                            @endif
                                        </td>
                                        <td class="py-3">
                                            <div class="flex gap-2">
                                                <button class="bg-green-100 hover:bg-green-200 text-green-800 text-sm px-3 py-1 rounded-lg">Approve</button>
                                                <button class="bg-red-100 hover:bg-red-200 text-red-800 text-sm px-3 py-1 rounded-lg">Reject</button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Quick Actions</h2>
                        <div class="space-y-3">
                            <button class="w-full bg-[#ff7700] hover:bg-orange-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 text-left">
                                <i class="fas fa-download mr-2"></i>
                                Download Report
                            </button>
                            <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200 text-left">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Details
                            </button>
                            <button class="w-full bg-green-100 hover:bg-green-200 text-green-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200 text-left">
                                <i class="fas fa-copy mr-2"></i>
                                Duplicate Event
                            </button>
                        </div>
                    </div>

                    <!-- Revenue Breakdown -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Revenue Breakdown</h2>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Booth Fees</span>
                                <span class="font-medium">Rp20,250,000</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Service Fees</span>
                                <span class="font-medium">Rp5,250,000</span>
                            </div>
                            <div class="border-t pt-3">
                                <div class="flex justify-between text-lg font-semibold">
                                    <span>Total Revenue</span>
                                    <span class="text-[#ff7700]">Rp25,500,000</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Event Timeline -->
                    <!-- <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Event Timeline</h2>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-check text-green-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Event Completed</p>
                                    <p class="text-xs text-gray-500">15 Sep 2024, 18:00</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-play text-green-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Event Started</p>
                                    <p class="text-xs text-gray-500">10 Sep 2024, 09:00</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-users text-blue-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Fully Booked</p>
                                    <p class="text-xs text-gray-500">20 Aug 2024, 14:30</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-rocket text-purple-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Event Published</p>
                                    <p class="text-xs text-gray-500">1 Jul 2024, 10:00</p>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <!-- Contact Info -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Contact Information</h2>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-[#ff7700] mr-3"></i>
                                <span class="text-gray-700 text-sm">info@techinnovationexpo.com</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-phone text-[#ff7700] mr-3"></i>
                                <span class="text-gray-700 text-sm">+62 21 5555 6789</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-globe text-[#ff7700] mr-3"></i>
                                <span class="text-gray-700 text-sm">www.techinnovationexpo.com</span>
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