<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Requests - Tech Innovation Expo 2025 - BoothEase</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])


</head>


@php
// Add this to your controller or at the top of your blade file in @php block

// Helper to format rupiah with dot thousand separators
if (!function_exists('formatRupiah')) {
function formatRupiah($value) {
$digits = preg_replace('/\D/', '', (string) $value);
$num = $digits === '' ? 0 : intval($digits);
return 'Rp' . number_format($num, 0, ',', '.');
}
}

// Sample booking requests data
$bookingRequests = [
[
'id' => 'REQ001',
'booth_number' => 'A01',
'location' => 'Hall 1',
'tenant' => 'Pegasus Peripherals',
'contact_person' => 'Darth Vader',
'phone' => '+62 812-3456-7890',
'price' => '500000',
'request_date' => '2025-09-01',
'status' => 'pending',
'notes' => 'Need power outlet near booth'
],
[
'id' => 'REQ002',
'booth_number' => 'A02',
'location' => 'Hall 2',
'tenant' => 'BallYards',
'contact_person' => 'Obi Wan',
'phone' => '+62 813-9876-5432',
'price' => '500000',
'request_date' => '2025-09-02',
'status' => 'approved',
'notes' => ''
],
[
'id' => 'REQ003',
'booth_number' => 'B01',
'location' => 'Main Hall',
'tenant' => 'HealthyGo',
'contact_person' => 'Jackie Chan',
'phone' => '+62 814-5555-1234',
'price' => '750000',
'request_date' => '2025-09-03',
'status' => 'rejected',
'notes' => 'Booth not suitable for equipment requirements'
],
[
'id' => 'REQ004',
'booth_number' => 'A03',
'location' => 'In front of entrance',
'tenant' => 'NourishScan',
'contact_person' => 'Chris Tucker',
'phone' => '+62 815-7777-8888',
'price' => '350000',
'request_date' => '2025-09-04',
'status' => 'pending',
'notes' => 'First-time exhibitor'
]
];

// Define table headers
$headers = [
['title' => 'Request ID', 'class' => 'w-30'],
['title' => 'Tenant Name', 'class' => 'w-40'],
['title' => 'Contact Person', 'class' => 'w-32'],
['title' => 'Booth', 'class' => 'w-20'],
['title' => 'Location', 'class' => 'w-28'],
['title' => 'Price', 'class' => 'w-28'],
['title' => 'Request Date', 'class' => 'w-28'],
['title' => 'Status', 'class' => 'w-24'],
['title' => 'Actions', 'class' => 'w-32'],
];

// Transform booking requests data into rows format
$rows = [];
foreach ($bookingRequests as $request) {
// Determine status styling
$statusColors = [
'pending' => 'bg-yellow-100 text-yellow-800',
'approved' => 'bg-green-100 text-green-800',
'rejected' => 'bg-red-100 text-red-800'
];

$statusColor = $statusColors[$request['status']] ?? 'bg-gray-100 text-gray-800';

// Generate action buttons based on status
$actionButtons = '';
if ($request['status'] === 'pending') {
$actionButtons = '
<div class="flex gap-2">
    <button
        class="bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1 rounded transition-colors">
        Approve
    </button>
    <button
        class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1 rounded transition-colors">
        Reject
    </button>
</div>';
} else {
$actionButtons = '<span class="text-gray-400 text-xs">No actions</span>';
}

$rows[] = [
'rowClass' => 'h-16 hover:bg-gray-50',
'cells' => [
[
'content' => $request['id'],
'class' => 'font-medium text-gray-900 text-sm'
],
[
'content' => $request['tenant'],
'class' => 'font-medium text-gray-900 text-sm'
],
[
'content' => '<div>
    <div class="text-sm text-gray-900">' . $request['contact_person'] . '</div>
    <div class="text-xs text-gray-500">' . $request['phone'] . '</div>
</div>',
'class' => ''
],
[
'content' => $request['booth_number'],
'class' => 'font-medium text-gray-900 text-sm'
],
[
'content' => $request['location'],
'class' => 'font-medium text-gray-900 text-sm'
],
[
'content' => formatRupiah($request['price']),
'class' => 'font-medium text-gray-900 text-sm'
],
[
'content' => date('M d, Y', strtotime($request['request_date'])),
'class' => 'text-sm text-gray-600'
],
[
'content' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $statusColor . '">' .
    ucfirst($request['status']) . '</span>',
'class' => ''
],
[
'content' => $actionButtons,
'class' => ''
]
]
];
}
@endphp

<body class="bg-gray-50 min-h-screen font-['Instrument_Sans']">
    <!-- Navbar -->
    @include('components.navbar')

    <!-- Main Content -->
    <div class="min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            @include('components.back-button', ['url' => url()->previous(), 'text' => 'Back to Event Details'])

            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Booking Requests</h1>
                        <p class="text-gray-600">Tech Innovation Expo 2025 • Technology • 16 - 20 November 2025</p>
                    </div>
                    <div class="flex items-center gap-3">
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Total Requests</p>
                            <p class="text-2xl font-bold text-gray-900">4</p>
                        </div>
                        <div class="bg-blue-100 w-12 h-12 flex items-center justify-center rounded-full">
                            <i class="fas fa-clipboard-list text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Pending</p>
                            <p class="text-2xl font-bold text-yellow-600">2</p>
                        </div>
                        <div class="bg-yellow-100 w-12 h-12 flex items-center justify-center rounded-full">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Approved</p>
                            <p class="text-2xl font-bold text-green-600">1</p>
                        </div>
                        <div class="bg-green-100 w-12 h-12 flex items-center justify-center rounded-full">
                            <i class="fas fa-check text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Rejected</p>
                            <p class="text-2xl font-bold text-red-600">1</p>
                        </div>
                        <div class="bg-red-100 w-12 h-12 flex items-center justify-center rounded-full">
                            <i class="fas fa-times text-red-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
                    <div class="flex flex-wrap gap-4 items-center">
                        <!-- Search -->
                        <div class="relative">
                            @include('components.search-bar', ['placeholder' => 'Search booking requests...'])

                        </div>

                        <!-- Status Filter -->
                        <div class="relative">
                            @include ('components.filter-button', ['label' => 'Status', 'id' => 'statusFilterBtn'])

                        </div>

                        <!-- Date Filter -->
                        <div class="flex items-center gap-2">
                            @include ('components.date-selector', ['label' => 'Start Date', 'id' => 'startDate'])
                            <span class="text-gray-500">to</span>
                            @include ('components.date-selector', ['label' => 'End Date', 'id' => 'endDate'])
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Requests Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">All Booking Requests</h2>
                    </div>
                </div>

                <!-- Table -->
                @include('components.table', [
                'headers' => $headers,
                'rows' => $rows,
                'tableClass' => 'w-full',
                'containerClass' => 'overflow-x-auto'
                ])

                <!-- Table Footer with Pagination -->
                <div class="px-6 py-2 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center justify-center">
                        @include('components.pagination', ['totalEntries' => 12, 'entriesPerPageOptions' => [10, 25, 50], 'currentEntriesPerPage' => 10])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('components.footer')

    <!-- Action Modal -->
    <div id="actionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-orange-100 mb-4">
                    <i id="modalIcon" class="fas fa-question text-orange-600 text-xl"></i>
                </div>
                <div class="text-center">
                    <h3 id="modalTitle" class="text-lg leading-6 font-medium text-gray-900">Confirm Action</h3>
                    <div class="mt-2 px-7 py-3">
                        <p id="modalMessage" class="text-sm text-gray-500"></p>
                    </div>
                    <div class="flex justify-center space-x-4 mt-4">
                        <button id="modalCancel" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition-colors">
                            Cancel
                        </button>
                        <button id="modalConfirm" class="px-4 py-2 bg-[#ff7700] text-white rounded hover:bg-orange-600 transition-colors">
                            Confirm
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- page-specific JS is loaded via Vite -->
</body>

</html>