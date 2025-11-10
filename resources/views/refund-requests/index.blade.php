<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Refund Requests</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@php

// Table headers
$headers = [
['title' => 'Request ID', 'class' => 'w-30'],
['title' => 'Tenant Name', 'class' => 'w-40'],
['title' => 'Contact Person', 'class' => 'w-32'],
['title' => 'Booth', 'class' => 'w-20'],
['title' => 'Amount', 'class' => 'w-28'],
['title' => 'Request Date', 'class' => 'w-28'],
['title' => 'Status', 'class' => 'w-24'],
['title' => 'Actions', 'class' => 'w-32'],
];

// Build rows for components.table
$rows = [];
foreach ($refundRequests as $refundRequest) {
// Status badge colors
$statusColors = [
'pending' => 'bg-yellow-100 text-yellow-800',
'approved' => 'bg-green-100 text-green-800',
'rejected' => 'bg-red-100 text-red-800'
];

$statusColor = $statusColors[strtolower($refundRequest->status)] ?? 'bg-gray-100 text-gray-800';

// Get related data
$booking = $refundRequest->booking;
$booth = $booking->booth ?? null;
$event = $booth->event ?? null;
$user = $refundRequest->user;

// Action button
$actionButtons = '<a href="' . route('refund-requests.show', ['event' => $event->id, 'refundRequest' => $refundRequest->id]) . '" class="inline-flex items-center px-3 py-1.5 rounded bg-[#ff7700] hover:bg-[#e66600] text-white text-sm">View</a>';

$rows[] = [
'rowClass' => 'h-16 hover:bg-gray-50',
'cells' => [
[
'content' => 'REF-' . str_pad($refundRequest->id, 4, '0', STR_PAD_LEFT),
'class' => 'font-medium text-gray-900 text-sm'
],
[
'content' => $user->name ?? 'N/A',
'class' => 'font-medium text-gray-900 text-sm'
],
[
'content' => '<div>
    <div class="text-sm text-gray-900">' . ($user->display_name ?? 'N/A') . '</div>
    <div class="text-xs text-gray-500">' . ($user && $user->phone_number ? formatPhoneNumber($user->phone_number) : 'N/A') . '</div>
</div>',
'class' => ''
],
[
'content' => $booth ? htmlspecialchars($booth->number) : 'N/A',
'class' => 'font-medium text-gray-900 text-sm'
],
[
'content' => formatRupiah($refundRequest->refund_amount ?? 0),
'class' => 'font-medium text-gray-900 text-sm'
],
[
'content' => $refundRequest->created_at->format('M d, Y'),
'class' => 'text-sm text-gray-600'
],
[
'content' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $statusColor . '">' .
    ucfirst($refundRequest->status) . '</span>',
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
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Refund Requests</h1>
                        <p class="text-gray-600">View and manage refund requests submitted by tenants</p>
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
                            <p class="text-2xl font-bold text-gray-900">{{ $totalRequests }}</p>
                        </div>
                        <div class="bg-blue-100 w-12 h-12 flex items-center justify-center rounded-full">
                            <i class="fas fa-hand-holding-usd text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Pending</p>
                            <p class="text-2xl font-bold text-yellow-600">{{ $pendingCount }}</p>
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
                            <p class="text-2xl font-bold text-green-600">{{ $approvedCount }}</p>
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
                            <p class="text-2xl font-bold text-red-600">{{ $rejectedCount }}</p>
                        </div>
                        <div class="bg-red-100 w-12 h-12 flex items-center justify-center rounded-full">
                            <i class="fas fa-times text-red-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <form method="GET" action="{{ route('refund-requests', ['event' => $event->id]) }}" class="flex flex-col lg:flex-row gap-4 items-center justify-between">
                    <div class="flex flex-wrap gap-4 items-center">
                        <!-- Search -->
                        <div class="relative">
                            <input type="text"
                                name="search"
                                value="{{ $filters['search'] ?? '' }}"
                                placeholder="Search refund requests..."
                                class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>

                        <!-- Status Filter -->
                        <div class="relative">
                            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-transparent">
                                <option value="">All Status</option>
                                <option value="pending" {{ in_array('pending', $filters['statuses'] ?? []) ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ in_array('approved', $filters['statuses'] ?? []) ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ in_array('rejected', $filters['statuses'] ?? []) ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>

                        <!-- Date Filter -->
                        <div class="flex items-center gap-2">
                            <input type="date"
                                name="start_date"
                                value="{{ $filters['start_date'] ?? '' }}"
                                class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-transparent">
                            <span class="text-gray-500">to</span>
                            <input type="date"
                                name="end_date"
                                value="{{ $filters['end_date'] ?? '' }}"
                                class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-transparent">
                        </div>

                        <!-- Filter Button -->
                        <button type="submit" class="px-4 py-2 bg-[#ff7700] text-white rounded-lg hover:bg-[#e66600] transition-colors">
                            Apply
                        </button>

                        <!-- Clear Filters -->
                        @if(!empty($filters['search']) || !empty($filters['statuses']) || !empty($filters['start_date']) || !empty($filters['end_date']))
                        <a href="{{ route('refund-requests', ['event' => $event->id]) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                            <i class="fas fa-times mr-2"></i>Clear
                        </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Refund Requests Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">All Refund Requests</h2>
                    </div>
                </div>

                <!-- Table -->
                @if($refundRequests->count() > 0)
                @include('components.table', [
                'headers' => $headers,
                'rows' => $rows,
                'tableClass' => 'w-full',
                'containerClass' => 'overflow-x-auto'
                ])

                <!-- Table Footer with Pagination -->
                <x-pagination :paginator="$refundRequests" />
                @else
                <div class="px-6 py-12 text-center">
                    <div class="max-w-md mx-auto">
                        <div class="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-inbox text-gray-400 text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No Refund Requests Found</h3>
                        <p class="text-gray-600 mb-6">
                            @if(!empty($filters['search']) || !empty($filters['statuses']) || !empty($filters['start_date']) || !empty($filters['end_date']))
                            No refund requests match your current filters. Try adjusting your search criteria.
                            @else
                            You haven't received any refund requests yet. They will appear here when tenants request refunds for their bookings.
                            @endif
                        </p>
                        @if(!empty($filters['search']) || !empty($filters['statuses']) || !empty($filters['start_date']) || !empty($filters['end_date']))
                        <a href="{{ route('refund-requests', ['event' => $event->id]) }}" class="inline-block bg-[#ff7700] hover:bg-[#e66600] text-white font-medium py-2 px-6 rounded-lg transition-colors duration-200">
                            Clear Filters
                        </a>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('components.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('filter-form');

            // Handle removing individual filters
            document.querySelectorAll('[data-remove-filter]').forEach(button => {
                button.addEventListener('click', function() {
                    const filterName = this.getAttribute('data-remove-filter');
                    const input = form.querySelector(`[name="${filterName}"]`);
                    if (input) {
                        input.value = '';
                        form.submit();
                    }
                });
            });

            // Handle removing status filters
            document.querySelectorAll('[data-remove-status]').forEach(button => {
                button.addEventListener('click', function() {
                    const status = this.getAttribute('data-remove-status');
                    const checkbox = form.querySelector(`[name="statuses[]"][value="${status}"]`);
                    if (checkbox) {
                        checkbox.checked = false;
                        form.submit();
                    }
                });
            });
        });
    </script>

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