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
    @viteCss
    @viteJs
</head>

@php

// Helper to get minimum booth price
if (!function_exists('getMinBoothPrice')) {
function getMinBoothPrice($event) {
$prices = [];
$boothConfig = $event->booth_configuration ?? [];

foreach ($boothConfig as $type => $config) {
if (isset($config['price'])) {
$prices[] = $config['price'];
}
}

return !empty($prices) ? min($prices) : 0;
}
}
@endphp

<body class="bg-gray-50 min-h-screen">
    <!-- Navbar -->
    @include('components.navbar')

    <!-- Main Content -->
    <div class="min-h-screen">
        @include('components.header', ['title' => 'All Events', 'subtitle' => 'Browse through our extensive list of events'])

        <!-- Search and Filter Section -->
        <section class="py-6 bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <form id="filter-form" method="GET" action="{{ route('events') }}" class="flex flex-col md:flex-row gap-4">
                    <!-- Search Bar -->
                    <div class="flex-1">
                        @include('components.search-bar', [
                        'placeholder' => 'Search by title, description, venue, or city...',
                        'value' => $filters['search'] ?? ''
                        ])
                    </div>

                    <!-- Filter Button -->
                    <x-filter-button
                        type="category"
                        label="Filter"
                        :categories="$allCategories"
                        :selectedCategories="$filters['categories'] ?? []"
                        :provinces="$allProvinces"
                        :cities="$allCities"
                        :selectedProvinceId="$filters['province_id'] ?? ''"
                        :selectedCityId="$filters['city_id'] ?? ''" />
                </form>

                <!-- Active Filters Display -->
                @if(!empty($filters['categories'] ?? []) || ($filters['province_id'] ?? '') || ($filters['city_id'] ?? ''))
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <span class="text-sm text-gray-600">Active filters:</span>

                    @foreach($filters['categories'] ?? [] as $categoryId)
                    @php
                    $category = $allCategories->find($categoryId);
                    @endphp
                    @if($category)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                        {{ $category->name }}
                        <button type="button" data-remove-category="{{ $categoryId }}" class="hover:cursor-pointer ml-2 hover:text-blue-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                    @endif
                    @endforeach

                    @if($filters['province_id'] ?? '')
                    @php
                    $province = $allProvinces->find($filters['province_id']);
                    @endphp
                    @if($province)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-purple-100 text-purple-800">
                        Province: {{ $province->name }}
                        <button type="button" data-remove-filter="province_id" class="hover:cursor-pointer ml-2 hover:text-purple-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                    @endif
                    @endif

                    @if($filters['city_id'] ?? '')
                    @php
                    $city = $allCities->find($filters['city_id']);
                    @endphp
                    @if($city)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-purple-100 text-purple-800">
                        City: {{ $city->name }}
                        <button type="button" data-remove-filter="city_id" class="hover:cursor-pointer ml-2 hover:text-purple-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                    @endif
                    @endif

                    <a href="{{ route('events') }}" class="text-sm text-[#ff7700] hover:text-[#e66600] font-medium">
                        Clear all filters
                    </a>
                </div>
                @endif
            </div>
        </section>

        <!-- Events Grid -->
        <section class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Section 1: Open for Registration -->
                @if($openForRegistration->count() > 0)
                <div class="mb-12">
                    <div class="flex items-center mb-6">
                        <!-- <i class="fas fa-calendar-check text-3xl text-green-600 mr-3"></i> -->
                        <h2 class="text-2xl font-bold text-gray-900">Open for Registration</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($openForRegistration as $event)
                        @php
                        $categoryColors = [
                        'text-blue-600', 'text-green-600', 'text-purple-600', 'text-red-600',
                        'text-orange-600', 'text-indigo-600', 'text-pink-600', 'text-teal-600',
                        'text-cyan-600', 'text-yellow-600'
                        ];
                        $gradients = [
                        ['from-blue-400', 'to-blue-600'],
                        ['from-green-400', 'to-green-600'],
                        ['from-purple-400', 'to-purple-600'],
                        ['from-red-400', 'to-red-600'],
                        ['from-yellow-400', 'to-orange-500'],
                        ['from-indigo-400', 'to-indigo-600'],
                        ['from-pink-400', 'to-pink-600'],
                        ['from-teal-400', 'to-teal-600'],
                        ['from-cyan-400', 'to-cyan-600'],
                        ];

                        $colorIndex = ($event->category_id ?? 0) % count($categoryColors);
                        $gradientIndex = ($event->category_id ?? 0) % count($gradients);
                        $categoryColor = $categoryColors[$colorIndex];
                        $gradient = $gradients[$gradientIndex];
                        $minPrice = getMinBoothPrice($event);
                        @endphp
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 relative">
                            @if($event->image_path)
                            <div class="h-48 relative">
                                <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                                <span class="absolute top-3 right-3 bg-white bg-opacity-90 {{ $categoryColor }} text-xs font-semibold px-2 py-1 rounded-full">
                                    {{ $event->category->name ?? 'Uncategorized' }}
                                </span>
                            </div>
                            @else
                            <div class="h-48 bg-gradient-to-br {{ $gradient[0] }} {{ $gradient[1] }} relative">
                                <span class="absolute top-3 right-3 bg-white bg-opacity-90 {{ $categoryColor }} text-xs font-semibold px-2 py-1 rounded-full">
                                    {{ $event->category->name ?? 'Uncategorized' }}
                                </span>
                            </div>
                            @endif
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $event->title }}</h3>
                                <div class="space-y-2 text-sm text-gray-600 mb-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-map-marker-alt mr-2 text-[#ff7700]"></i>
                                        <span>{{ $event->display_location ?? 'Location TBA' }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-alt mr-2 text-[#ff7700]"></i>
                                        <span>{{ $event->start_time ? $event->start_time->format('d F Y') : 'Date TBA' }}</span>
                                    </div>
                                    @if($event->registration_deadline)
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-2 text-[#ff7700]"></i>
                                        <span>Register by: {{ $event->registration_deadline->format('d F Y') }}</span>
                                    </div>
                                    @endif
                                    <div class="flex items-center">
                                        <i class="fas fa-store mr-2 text-[#ff7700]"></i>
                                        <span>{{ $event->available_booths_count ?? $event->booths_count ?? 0 }} Booths Available</span>
                                    </div>
                                    @if($minPrice > 0)
                                    <div class="flex items-center">
                                        <i class="fas fa-tag mr-2 text-[#ff7700]"></i>
                                        <span>Starting from: {{ formatRupiah($minPrice) }}</span>
                                    </div>
                                    @endif
                                </div>
                                <a href="{{ route('events.show', $event->id) }}" class="block w-full bg-gray-100 hover:bg-[#ff7700] hover:text-white text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200 text-center">
                                    View Details
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Section 2: Registration Closed -->
                @if($registrationClosed->count() > 0)
                <div class="mb-12">
                    <div class="flex items-center mb-6">
                        <i class="fas fa-calendar-times text-3xl text-orange-600 mr-3"></i>
                        <h2 class="text-2xl font-bold text-gray-900">Registration Closed</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($registrationClosed as $event)
                        @php
                        $categoryColors = [
                        'text-blue-600', 'text-green-600', 'text-purple-600', 'text-red-600',
                        'text-orange-600', 'text-indigo-600', 'text-pink-600', 'text-teal-600',
                        'text-cyan-600', 'text-yellow-600'
                        ];
                        $gradients = [
                        ['from-blue-400', 'to-blue-600'],
                        ['from-green-400', 'to-green-600'],
                        ['from-purple-400', 'to-purple-600'],
                        ['from-red-400', 'to-red-600'],
                        ['from-yellow-400', 'to-orange-500'],
                        ['from-indigo-400', 'to-indigo-600'],
                        ['from-pink-400', 'to-pink-600'],
                        ['from-teal-400', 'to-teal-600'],
                        ['from-cyan-400', 'to-cyan-600'],
                        ];

                        $colorIndex = ($event->category_id ?? 0) % count($categoryColors);
                        $gradientIndex = ($event->category_id ?? 0) % count($gradients);
                        $categoryColor = $categoryColors[$colorIndex];
                        $gradient = $gradients[$gradientIndex];
                        $minPrice = getMinBoothPrice($event);
                        @endphp
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 relative opacity-90">
                            @if($event->image_path)
                            <div class="h-48 relative">
                                <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                                <span class="absolute top-3 left-3 bg-orange-500 text-white text-xs font-semibold px-2 py-1 rounded-full">
                                    <i class="fas fa-lock mr-1"></i> Closed
                                </span>
                                <span class="absolute top-3 right-3 bg-white bg-opacity-90 {{ $categoryColor }} text-xs font-semibold px-2 py-1 rounded-full">
                                    {{ $event->category->name ?? 'Uncategorized' }}
                                </span>
                            </div>
                            @else
                            <div class="h-48 bg-gradient-to-br {{ $gradient[0] }} {{ $gradient[1] }} relative">
                                <span class="absolute top-3 left-3 bg-orange-500 text-white text-xs font-semibold px-2 py-1 rounded-full">
                                    <i class="fas fa-lock mr-1"></i> Closed
                                </span>
                                <span class="absolute top-3 right-3 bg-white bg-opacity-90 {{ $categoryColor }} text-xs font-semibold px-2 py-1 rounded-full">
                                    {{ $event->category->name ?? 'Uncategorized' }}
                                </span>
                            </div>
                            @endif
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $event->title }}</h3>
                                <div class="space-y-2 text-sm text-gray-600 mb-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-map-marker-alt mr-2 text-[#ff7700]"></i>
                                        <span>{{ $event->display_location ?? 'Location TBA' }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-alt mr-2 text-[#ff7700]"></i>
                                        <span>{{ $event->start_time ? $event->start_time->format('d F Y') : 'Date TBA' }}</span>
                                    </div>
                                    @if($event->registration_deadline)
                                    <div class="flex items-center text-orange-600 font-medium">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        <span>Registration ended: {{ $event->registration_deadline->format('d F Y') }}</span>
                                    </div>
                                    @endif
                                    <div class="flex items-center">
                                        <i class="fas fa-store mr-2 text-[#ff7700]"></i>
                                        <span>{{ $event->available_booths_count ?? $event->booths_count ?? 0 }} Booths Available</span>
                                    </div>
                                    @if($minPrice > 0)
                                    <div class="flex items-center">
                                        <i class="fas fa-tag mr-2 text-[#ff7700]"></i>
                                        <span>Starting from: {{ formatRupiah($minPrice) }}</span>
                                    </div>
                                    @endif
                                </div>
                                <a href="{{ route('events.show', $event->id) }}" class="block w-full bg-gray-500 hover:bg-gray-600 text-white text-sm py-2 px-3 rounded-lg transition-colors duration-200 text-center">
                                    View Details
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Section 3: Ongoing Events -->
                @if($ongoingEvents->count() > 0)
                <div class="mb-12">
                    <div class="flex items-center mb-6">
                        <!-- <i class="fas fa-circle-notch fa-spin text-3xl text-[#ff7700] mr-3"></i> -->
                        <h2 class="text-2xl font-bold text-gray-900">Ongoing Events</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($ongoingEvents as $event)
                        @php
                        $categoryColors = [
                        'text-blue-600', 'text-green-600', 'text-purple-600', 'text-red-600',
                        'text-orange-600', 'text-indigo-600', 'text-pink-600', 'text-teal-600',
                        'text-cyan-600', 'text-yellow-600'
                        ];
                        $gradients = [
                        ['from-blue-400', 'to-blue-600'],
                        ['from-green-400', 'to-green-600'],
                        ['from-purple-400', 'to-purple-600'],
                        ['from-red-400', 'to-red-600'],
                        ['from-yellow-400', 'to-orange-500'],
                        ['from-indigo-400', 'to-indigo-600'],
                        ['from-pink-400', 'to-pink-600'],
                        ['from-teal-400', 'to-teal-600'],
                        ['from-cyan-400', 'to-cyan-600'],
                        ];

                        $colorIndex = ($event->category_id ?? 0) % count($categoryColors);
                        $gradientIndex = ($event->category_id ?? 0) % count($gradients);
                        $categoryColor = $categoryColors[$colorIndex];
                        $gradient = $gradients[$gradientIndex];
                        $minPrice = getMinBoothPrice($event);
                        @endphp
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 relative">
                            @if($event->image_path)
                            <div class="h-48 relative">
                                <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                                <span class="absolute top-3 right-3 bg-white bg-opacity-90 {{ $categoryColor }} text-xs font-semibold px-2 py-1 rounded-full">
                                    {{ $event->category->name ?? 'Uncategorized' }}
                                </span>
                            </div>
                            @else
                            <div class="h-48 bg-gradient-to-br {{ $gradient[0] }} {{ $gradient[1] }} relative">
                                <span class="absolute top-3 right-3 bg-white bg-opacity-90 {{ $categoryColor }} text-xs font-semibold px-2 py-1 rounded-full">
                                    {{ $event->category->name ?? 'Uncategorized' }}
                                </span>
                            </div>
                            @endif
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $event->title }}</h3>
                                <div class="space-y-2 text-sm text-gray-600 mb-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-map-marker-alt mr-2 text-[#ff7700]"></i>
                                        <span>{{ $event->display_location ?? 'Location TBA' }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-alt mr-2 text-[#ff7700]"></i>
                                        <span>{{ $event->start_time ? $event->start_time->format('d F Y') : 'Date TBA' }} - {{ $event->end_time ? $event->end_time->format('d F Y') : 'TBA' }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-store mr-2 text-[#ff7700]"></i>
                                        <span>{{ $event->available_booths_count ?? $event->booths_count ?? 0 }} Booths Available</span>
                                    </div>
                                    @if($minPrice > 0)
                                    <div class="flex items-center">
                                        <i class="fas fa-tag mr-2 text-[#ff7700]"></i>
                                        <span>Starting from: {{ formatRupiah($minPrice) }}</span>
                                    </div>
                                    @endif
                                </div>
                                <a href="{{ route('events.show', $event->id) }}" class="block w-full bg-gray-100 hover:bg-[#ff7700] hover:text-white text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200 text-center">
                                    View Details
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Section 4: Completed Events -->
                @if($completedEvents->count() > 0)
                <div class="mb-12">
                    <div class="flex items-center mb-6">
                        <i class="fas fa-check-circle text-3xl text-gray-600 mr-3"></i>
                        <h2 class="text-2xl font-bold text-gray-900">Completed Events</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($completedEvents as $event)
                        @php
                        $categoryColors = [
                        'text-blue-600', 'text-green-600', 'text-purple-600', 'text-red-600',
                        'text-orange-600', 'text-indigo-600', 'text-pink-600', 'text-teal-600',
                        'text-cyan-600', 'text-yellow-600'
                        ];
                        $gradients = [
                        ['from-blue-400', 'to-blue-600'],
                        ['from-green-400', 'to-green-600'],
                        ['from-purple-400', 'to-purple-600'],
                        ['from-red-400', 'to-red-600'],
                        ['from-yellow-400', 'to-orange-500'],
                        ['from-indigo-400', 'to-indigo-600'],
                        ['from-pink-400', 'to-pink-600'],
                        ['from-teal-400', 'to-teal-600'],
                        ['from-cyan-400', 'to-cyan-600'],
                        ];

                        $colorIndex = ($event->category_id ?? 0) % count($categoryColors);
                        $gradientIndex = ($event->category_id ?? 0) % count($gradients);
                        $categoryColor = $categoryColors[$colorIndex];
                        $gradient = $gradients[$gradientIndex];
                        $minPrice = getMinBoothPrice($event);
                        @endphp
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 relative opacity-75">
                            @if($event->image_path)
                            <div class="h-48 relative grayscale">
                                <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                                <span class="absolute top-3 left-3 bg-gray-600 text-white text-xs font-semibold px-2 py-1 rounded-full">
                                    <i class="fas fa-flag-checkered mr-1"></i> Completed
                                </span>
                                <span class="absolute top-3 right-3 bg-white bg-opacity-90 {{ $categoryColor }} text-xs font-semibold px-2 py-1 rounded-full">
                                    {{ $event->category->name ?? 'Uncategorized' }}
                                </span>
                            </div>
                            @else
                            <div class="h-48 bg-gradient-to-br {{ $gradient[0] }} {{ $gradient[1] }} relative grayscale">
                                <span class="absolute top-3 left-3 bg-gray-600 text-white text-xs font-semibold px-2 py-1 rounded-full">
                                    <i class="fas fa-flag-checkered mr-1"></i> Completed
                                </span>
                                <span class="absolute top-3 right-3 bg-white bg-opacity-90 {{ $categoryColor }} text-xs font-semibold px-2 py-1 rounded-full">
                                    {{ $event->category->name ?? 'Uncategorized' }}
                                </span>
                            </div>
                            @endif
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $event->title }}</h3>
                                <div class="space-y-2 text-sm text-gray-600 mb-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-map-marker-alt mr-2 text-gray-500"></i>
                                        <span>{{ $event->display_location ?? 'Location TBA' }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-alt mr-2 text-gray-500"></i>
                                        <span>{{ $event->end_time ? $event->end_time->format('d F Y') : 'Date TBA' }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-store mr-2 text-gray-500"></i>
                                        <span>{{ $event->booths_count ?? 0 }} Total Booths</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- No Events Message -->
                @if($openForRegistration->count() == 0 && $registrationClosed->count() == 0 && $ongoingEvents->count() == 0 && $completedEvents->count() == 0)
                <div class="text-center py-12">
                    <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No Events Available</h3>
                    <p class="text-gray-500">There are currently no published events. Please check back later.</p>
                </div>
                @endif
            </div>
        </section>
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

            // Handle removing category filters
            document.querySelectorAll('[data-remove-category]').forEach(button => {
                button.addEventListener('click', function() {
                    const categoryId = this.getAttribute('data-remove-category');
                    const checkbox = form.querySelector(`[name="categories[]"][value="${categoryId}"]`);
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