<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Bookings - BoothEase</title>

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

@endphp

<body class="bg-gray-50 min-h-screen">
    <!-- Navbar -->
    @include('components.navbar')

    <!-- Main Content -->
    <div class="min-h-screen mb-8">
        <!-- Header -->
        @include('components.header', ['title' => 'My Bookings', 'subtitle' => 'Manage Your Booth Reservations'])

        <!-- Search and Filter Section -->
        <section class="py-6 bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <form id="filter-form" method="GET" action="{{ route('my-bookings') }}" class="flex flex-col md:flex-row gap-4">
                    <!-- Search Bar -->
                    <div class="flex-1">
                        @include('components.search-bar', [
                        'placeholder' => 'Search by event title, venue, booth name, or booking ID...',
                        'value' => $filters['search'] ?? ''
                        ])
                    </div>

                    <!-- Filter Button -->
                    <x-filter-button
                        type="status"
                        label="Filter"
                        :selectedStatuses="$filters['statuses'] ?? []"
                        :minPrice="$filters['min_price'] ?? ''"
                        :maxPrice="$filters['max_price'] ?? ''" />
                </form>

                <!-- Active Filters Display -->
                @if(!empty($filters['statuses'] ?? []) || ($filters['min_price'] ?? '') || ($filters['max_price'] ?? ''))
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <span class="text-sm text-gray-600">Active filters:</span>

                    @foreach($filters['statuses'] ?? [] as $status)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                        {{ ucfirst($status) }}
                        <button type="button" data-remove-status="{{ $status }}" class="hover:cursor-pointer ml-2 hover:text-blue-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                    @endforeach

                    @if($filters['min_price'] ?? '')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">
                        Min: {{ formatRupiah($filters['min_price']) }}
                        <button type="button" data-remove-filter="min_price" class="hover:cursor-pointer ml-2 hover:text-green-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                    @endif

                    @if($filters['max_price'] ?? '')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">
                        Max: {{ formatRupiah($filters['max_price']) }}
                        <button type="button" data-remove-filter="max_price" class="hover:cursor-pointer ml-2 hover:text-green-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                    @endif

                    <a href="{{ route('my-bookings') }}" class="text-sm text-[#ff7700] hover:text-[#e66600] font-medium">
                        Clear all filters
                    </a>
                </div>
                @endif
            </div>
        </section>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Bookings -->
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-left">
                            <p class="text-sm text-gray-600 mb-1">Total Bookings</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $totalBookings }}</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-store text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Confirmed -->
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-left">
                            <p class="text-sm text-gray-600 mb-1">Completed</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $completedBookings }}</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Spent -->
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-left">
                            <p class="text-sm text-gray-600 mb-1">Total Spent</p>
                            <p class="text-3xl font-bold text-gray-900">{{ formatRupiah($totalSpent) }}</p>
                        </div>
                        <div class="bg-orange-100 p-3 rounded-full">
                            <i class="fas fa-money-bill text-[#ff7700] text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Cards -->
            <div>
                @forelse($bookings as $booking)
                @php
                $event = $booking->booth->event;
                $statusDisplay = getBookingStatusDisplay($booking->status);

                // Format event dates and times using helper functions
                $dateDisplay = formatEventDate($event);
                $timeDisplay = formatEventTime($event);
                @endphp

                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100 m-6">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $event->title }}</h3>
                                <div class="flex items-center mb-1">
                                    <i class="fas fa-map-marker-alt mr-2 text-[#ff7700]"></i>
                                    <p class="text-sm text-gray-600">{{ $event->display_location ?? $event->venue ?? 'Location not specified' }}</p>
                                </div>
                                <div class="text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-alt mr-2 text-[#ff7700]"></i>
                                        <span>{{ $dateDisplay }}</span>
                                    </div>
                                    @if($timeDisplay)
                                    <div class="mt-1 flex items-center">
                                        <i class="fa-regular fa-clock mr-2 text-[#ff7700]"></i>
                                        <span>{{ $timeDisplay }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusDisplay['class'] }}">
                                {{ $statusDisplay['label'] }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                            <!-- Booth Details -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Booth Details</h4>
                                <p class="text-sm text-gray-900 font-medium">{{ $booking->booth->name }}</p>
                                @if($booking->booth->type)
                                <p class="text-xs text-gray-600">{{ ucfirst($booking->booth->type) }} Type</p>
                                @endif
                            </div>

                            <!-- Booking Date -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Booking Date</h4>
                                <p class="text-sm text-gray-900">{{ $booking->booking_date->format('d-m-Y') }}</p>
                            </div>

                            <!-- Total Price -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Total Price</h4>
                                <p class="text-sm font-semibold text-[#ff7700]">{{ formatRupiah($booking->total_price) }}</p>
                            </div>

                            <!-- Booking ID -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Booking ID</h4>
                                <p class="text-sm text-gray-900">ID-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>

                        <!-- Booth Information -->
                        @if($booking->booth->size || $booking->notes)
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Additional Information</h4>
                            <div class="flex flex-wrap gap-2">
                                @if($booking->booth->size)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-ruler-combined mr-1"></i> {{ $booking->booth->size }}
                                </span>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex gap-3">
                            @if($booking->status === 'paid' && $event->refundable && !$booking->refundRequest)
                            <a href="{{ route('request-refund', ['booking' => $booking->id]) }}" class="hover:cursor-pointer bg-red-50 hover:bg-red-100 text-red-600 font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                <i class="fas fa-undo mr-2"></i>
                                Request Refund
                            </a>
                            @endif

                            @if($booking->refundRequest)
                            @if($booking->refundRequest->isPending())
                            <span class="bg-yellow-50 text-yellow-600 font-medium py-2 px-4 rounded-lg border border-yellow-200">
                                <i class="fas fa-clock mr-2"></i>
                                Refund Pending
                            </span>
                            @elseif($booking->refundRequest->isApproved())
                            <span class="bg-green-50 text-green-600 font-medium py-2 px-4 rounded-lg border border-green-200">
                                <i class="fas fa-check-circle mr-2"></i>
                                Refund Approved
                            </span>
                            @elseif($booking->refundRequest->isRejected())
                            <span class="bg-red-50 text-red-600 font-medium py-2 px-4 rounded-lg border border-red-200">
                                <i class="fas fa-times-circle mr-2"></i>
                                Refund Rejected
                            </span>
                            @endif
                            @endif

                            <a href="{{ route('my-booking-details', $booking->id) }}" class="hover:cursor-pointer bg-[#ff7700] hover:bg-[#e66600] text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 inline-block text-center">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <div class="max-w-md mx-auto">
                        <div class="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-inbox text-gray-400 text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No Bookings Yet</h3>
                        <p class="text-gray-600 mb-6">You haven't made any booth bookings yet. Start exploring events and book your booth today!</p>
                        <a href="{{ route('events') }}" class="inline-block bg-[#ff7700] hover:bg-[#e66600] text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200">
                            Browse Events
                        </a>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($bookings->hasPages() || $bookings->total() > 5)
            <x-pagination :paginator="$bookings" />
            @endif

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
</body>

</html>