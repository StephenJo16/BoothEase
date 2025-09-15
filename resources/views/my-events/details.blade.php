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

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@php
// Define booking requests data
$bookingRequests = [
['booth' => 'A01', 'business' => 'Pegasus Peripherals', 'requestedOn' => '15 Oct 2025', 'price' => '1200000', 'status' => 'Pending'],
['booth' => 'B05', 'business' => 'BallYards', 'requestedOn' => '12 Oct 2025', 'price' => '1200000', 'status' => 'Pending'],
['booth' => 'A06', 'business' => 'HealthyGo', 'requestedOn' => '10 Oct 2025', 'price' => '1200000', 'status' => 'Pending'],
];

// Helper to format rupiah with dot thousand separators
if (!function_exists('formatRupiah')) {
function formatRupiah($value) {
$digits = preg_replace('/\D/', '', (string) $value);
$num = $digits === '' ? 0 : intval($digits);
return 'Rp' . number_format($num, 0, ',', '.');
}
}

// Revenue numbers (integers, in IDR)
$boothFees = 20250000; // amount that goes to organizer
$serviceFees = 405000; // platform/service fees
$totalCollected = $boothFees + $serviceFees; // total money collected
$organizerRevenue = $boothFees - $serviceFees; // organizer receives booth fees minus service fees

// Prepare table headers
$headers = [
['title' => 'Booth', 'class' => 'text-sm'],
['title' => 'Business Name', 'class' => 'text-sm'],
['title' => 'Requested On', 'class' => 'text-sm'],
['title' => 'Price', 'class' => 'text-sm'],
['title' => 'Status', 'class' => 'text-sm'],
['title' => 'Action', 'class' => 'text-sm']
];

// Prepare table rows
$rows = [];
foreach ($bookingRequests as $request) {
// Determine status badge HTML
$statusBadge = '';
if ($request['status'] === 'Pending') {
$statusBadge = '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>';
} elseif ($request['status'] === 'Approved') {
$statusBadge = '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Approved</span>';
} elseif ($request['status'] === 'Rejected') {
$statusBadge = '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Rejected</span>';
} else {
$statusBadge = '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">' . $request['status'] . '</span>';
}

// Action buttons HTML
$actionButtons = '<div class="flex gap-2">'
    . '<button class="hover:cursor-pointer bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1 rounded transition-colors">Approve</button>'
    . '<button class="hover:cursor-pointer bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1 rounded transition-colors">Reject</button>'
    . '</div>';


$rows[] = [
'rowClass' => 'h-20',
'cells' => [
['content' => $request['booth'], 'class' => 'text-sm font-medium text-gray-900'],
['content' => $request['business'], 'class' => 'text-sm text-gray-600'],
['content' => $request['requestedOn'], 'class' => 'text-sm text-gray-600'],
['content' => formatRupiah($request['price']), 'class' => 'text-sm text-gray-900'],
['content' => $statusBadge, 'class' => ''],
['content' => $actionButtons, 'class' => '']
]
];
}
@endphp

<body class="bg-gray-50 min-h-screen">
    <!-- Navbar -->
    @include('components.navbar')

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
                        <button class="bg-[#ff7700] hover:bg-[#e66600]hover:cursor-pointer text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
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
                            <p class="text-2xl font-bold text-gray-900">{{ formatRupiah($organizerRevenue) }}</p>
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

                    <!-- Booking Requests -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold text-gray-900">Booking Requests</h2>
                            <a href="{{ route('booking-requests') }}" class="text-[#ff7700] hover:text-orange-600 text-sm font-medium">View All</a>
                        </div>

                        @include('components.table', [
                        'headers' => $headers,
                        'rows' => $rows,
                        'tableClass' => 'w-full',
                        'containerClass' => 'overflow-x-auto'
                        ])
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Quick Actions</h2>
                        <div class="space-y-3">
                            <button class="w-full bg-[#ff7700] hover:bg-[#e66600] hover:cursor-pointer text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 text-left">
                                <i class="fas fa-download mr-2"></i>
                                Download Report
                            </button>
                            <button class="w-full bg-gray-100 hover:bg-gray-200 hover:cursor-pointer text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200 text-left">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Details
                            </button>
                        </div>
                    </div>

                    <!-- Revenue Breakdown -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Revenue Breakdown</h2>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Booth Fees</span>
                                <span class="font-medium">{{ formatRupiah($boothFees) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Service Fees</span>
                                <span class="font-medium">{{ formatRupiah($serviceFees) }}</span>
                            </div>
                            <div class="border-t pt-3">
                                <div class="flex justify-between text-lg font-semibold">
                                    <span>Organizer Revenue</span>
                                    <span class="text-[#ff7700]">{{ formatRupiah($organizerRevenue) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

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