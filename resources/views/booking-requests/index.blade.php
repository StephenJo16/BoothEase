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
    @viteCss
    @viteJs


</head>


@php
// Define table headers
$headers=[
['title'=> 'Request ID', 'class' => 'w-30'],
['title' => 'Tenant', 'class' => 'w-40'],
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
// Get booking status display properties
$bookingStatus = getBookingStatusDisplay($booking->status);

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
    <div class="text-xs text-gray-500">' . ($booking->user && $booking->user->phone_number ? formatPhoneNumber($booking->user->phone_number) : 'N/A') . '</div>
</div>',
'class' => ''
],
[
'content' => $booking->booth->name ?? 'N/A',
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
'content' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $bookingStatus['class'] . '">' .
    $bookingStatus['label'] . '</span>',
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
                        <p class="text-gray-600">{{ $event->title }} • {{ $event->start_time->isSameDay($event->end_time) ? $event->start_time->format('d M Y') : $event->start_time->format('d') . ' - ' . $event->end_time->format('d M Y') }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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
                <form method="GET" action="{{ route('booking-requests', ['event' => $event->id]) }}" class="flex flex-col lg:flex-row gap-4 items-center justify-between">
                    <div class="flex flex-wrap gap-4 items-center">
                        <!-- Search -->
                        <div class="relative">
                            <input type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search booking requests..."
                                class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>

                        <!-- Status Filter -->
                        <div class="relative">
                            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-transparent">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                        </div>

                        <!-- Date Filter -->
                        <div class="flex items-center gap-2">
                            <input type="date"
                                name="start_date"
                                value="{{ request('start_date') }}"
                                class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-transparent">
                            <span class="text-gray-500">to</span>
                            <input type="date"
                                name="end_date"
                                value="{{ request('end_date') }}"
                                class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-transparent">
                        </div>

                        <!-- Filter Button -->
                        <button type="submit" class="hover:cursor-pointer px-4 py-2 bg-[#ff7700] text-white rounded-lg hover:bg-[#e66600] transition-colors">
                            Apply
                        </button>

                        <!-- Clear Filters -->
                        @if(request()->hasAny(['search', 'status', 'start_date', 'end_date']))
                        <a href="{{ route('booking-requests', ['event' => $event->id]) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                            <i class="fas fa-times mr-2"></i>Clear
                        </a>
                        @endif
                    </div>
                </form>
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
                <x-pagination :paginator="$bookings" />
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('components.footer')

    <!-- page-specific JS is loaded via Vite -->
</body>

</html>