<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Requests - {{ $event->title }} - BoothEase</title>

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
$digits = preg_replace('/\D/', '', (string) $value);
$num = $digits === '' ? 0 : intval($digits);
return 'Rp' . number_format($num, 0, ',', '.');
}
}

// Define table headers
$headers = [
['title' => 'Request ID', 'class' => 'w-30'],
['title' => 'Tenant Name', 'class' => 'w-40'],
['title' => 'Contact Person', 'class' => 'w-32'],
['title' => 'Booth', 'class' => 'w-20'],
['title' => 'Price', 'class' => 'w-28'],
['title' => 'Request Date', 'class' => 'w-28'],
['title' => 'Status', 'class' => 'w-24'],
['title' => 'Actions', 'class' => 'w-32'],
];

// Transform booking requests data into rows format
$rows = [];
foreach ($bookings as $booking) {
// Determine status styling
$statusColors = [
'pending' => 'bg-yellow-100 text-yellow-800',
'confirmed' => 'bg-green-100 text-green-800',
'rejected' => 'bg-red-100 text-red-800',
'cancelled' => 'bg-gray-100 text-gray-800'
];

$statusColor = $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800';

// Display status
$statusDisplay = [
'confirmed' => 'Confirmed',
'pending' => 'Pending',
'rejected' => 'Rejected',
'cancelled' => 'Cancelled'
];

$displayStatus = $statusDisplay[$booking->status] ?? ucfirst($booking->status);

// Action: View link
$actionButtons = '<a href="' . route('booking-request-details', ['event' => $event->id, 'booking' => $booking->id]) . '" class="inline-flex items-center px-3 py-1.5 rounded bg-[#ff7700] hover:bg-[#e66600] text-white text-sm">View</a>';

$rows[] = [
'rowClass' => 'h-16 hover:bg-gray-50',
'cells' => [
[
'content' => 'REQ' . str_pad($booking->id, 3, '0', STR_PAD_LEFT),
'class' => 'font-medium text-gray-900 text-sm'
],
[
'content' => $booking->user->name ?? 'N/A',
'class' => 'font-medium text-gray-900 text-sm'
],
[
'content' => '<div>
    <div class="text-sm text-gray-900">' . ($booking->user->display_name ?? 'N/A') . '</div>
    <div class="text-xs text-gray-500">' . ($booking->user->phone_number ?? 'N/A') . '</div>
</div>',
'class' => ''
],
[
'content' => $booking->booth->number ?? 'N/A',
'class' => 'font-medium text-gray-900 text-sm'
],
[
'content' => formatRupiah($booking->total_price),
'class' => 'font-medium text-gray-900 text-sm'
],
[
'content' => $booking->created_at->format('M d, Y'),
'class' => 'text-sm text-gray-600'
],
[
'content' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $statusColor . '">' .
    $displayStatus . '</span>',
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
            @include('components.back-button', ['url' => route('my-events.show', ['event' => $event->id]), 'text' => 'Back to Event Details'])

            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Booking Requests</h1>
                        <p class="text-gray-600">{{ $event->title }} • {{ $event->start_time->format('d') }} - {{ $event->end_time->format('d M Y') }}</p>
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
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
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
                            <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                        </div>
                        <div class="bg-yellow-100 w-12 h-12 flex items-center justify-center rounded-full">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Confirmed</p>
                            <p class="text-2xl font-bold text-green-600">{{ $stats['confirmed'] }}</p>
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
                            <p class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
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
                            @include ('components.filter-button', ['label' => 'Filter', 'id' => 'filterBtn'])

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
                        @include('components.pagination', ['totalEntries' => $bookings->count(), 'entriesPerPageOptions' => [10, 25, 50], 'currentEntriesPerPage' => 10])
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
                        <button id="modalConfirm" class="px-4 py-2 bg-[#ff7700] text-white rounded hover:bg-[#e66600] transition-colors">
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