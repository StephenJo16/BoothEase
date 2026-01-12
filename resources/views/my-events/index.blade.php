<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Events</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @viteCss
    @viteJs
</head>

<body class="bg-gray-50 min-h-screen">
    @include('components.navbar')

    <div class="min-h-screen">
        @include('components.header', ['title' => 'My Events', 'subtitle' => 'Manage your events'])

        <!-- Search and Filter Section -->
        <section class="bg-white border-b border-gray-200 py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <form id="filter-form" method="GET" action="{{ route('my-events.index') }}" class="flex flex-col md:flex-row gap-4">
                    <!-- Search Bar -->
                    <div class="flex-1">
                        @include('components.search-bar', [
                        'placeholder' => 'Search by title, description, venue, or city...',
                        'value' => $filters['search'] ?? ''
                        ])
                    </div>

                    <!-- Combined Filter Button -->
                    <x-filter-button
                        type="combined"
                        label="Filter"
                        :categories="$allCategories"
                        :selectedCategories="$filters['categories'] ?? []"
                        :selectedStatuses="$filters['statuses'] ?? []"
                        :provinces="$allProvinces"
                        :cities="$allCities"
                        :selectedProvinceId="$filters['province_id'] ?? ''"
                        :selectedCityId="$filters['city_id'] ?? ''"
                        :refundable="$filters['refundable'] ?? ''" />
                </form>

                <!-- Active Filters Display -->
                @if(!empty($filters['categories'] ?? []) || !empty($filters['statuses'] ?? []) || ($filters['province_id'] ?? '') || ($filters['city_id'] ?? '') || ($filters['refundable'] ?? ''))
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

                    @foreach($filters['statuses'] ?? [] as $status)
                    @php
                    $statusLabels = [
                    'draft' => 'Draft',
                    'finalized' => 'Finalized',
                    'published' => 'Published',
                    'ongoing' => 'Ongoing',
                    'completed' => 'Completed',
                    ];
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">
                        {{ $statusLabels[$status] ?? ucfirst($status) }}
                        <button type="button" data-remove-status="{{ $status }}" class="hover:cursor-pointer ml-2 hover:text-green-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
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

                    @if($filters['refundable'] ?? '')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">
                        Refundable Only
                        <button type="button" data-remove-filter="refundable" class="hover:cursor-pointer ml-2 hover:text-green-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                    @endif

                    <a href="{{ route('my-events.index') }}" class="text-sm text-[#ff7700] hover:text-[#e66600] font-medium">
                        Clear all filters
                    </a>
                </div>
                @endif
            </div>
        </section>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                <div>
                    <p class="text-sm text-gray-600">View, edit, and publish the events you manage.</p>
                </div>
                <a href="{{ route('my-events.create') }}" class="inline-flex items-center justify-center rounded-lg bg-[#ff7700] px-4 py-2 text-sm font-semibold text-white transition-colors duration-200 hover:bg-[#e66600]">
                    <i class="fa-solid fa-plus mr-2"></i>
                    Create New Event
                </a>
            </div>

            @if($events->isEmpty())
            <div class="text-center py-12">
                <i class="fa-solid fa-calendar-plus text-6xl text-gray-300 mb-4"></i>
                <p class="text-xl font-semibold text-gray-700 mb-2">You have not created any events yet.</p>
                <p class="text-gray-500">Start by creating your first event to manage booths and bookings.</p>
            </div>
            @else
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($events as $event)
                @php
                $status = $event->status;
                $badge = getEventStatusDisplay($status);
                $boothTypes = $event->booths->pluck('type')->unique()->count();
                $boothTotal = $event->booths_count ?? 0;
                $bookedBooths = $event->booked_booths_count ?? 0;
                $availableBooths = $event->available_booths_count ?? 0;

                // Format event dates and times using helper functions
                $dateDisplay = formatEventDate($event);
                $timeDisplay = formatEventTime($event);

                $location = $event->display_location ?: 'Location to be confirmed';
                $category = optional($event->category)->name ?: 'Uncategorised';

                // Get category colors
                $colorScheme = getCategoryColors($event->category_id);
                $categoryColor = $colorScheme['color'];
                $gradient = $colorScheme['gradient'];
                @endphp
                <div class="flex h-full flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    @if($event->image_path)
                    <div class="h-48 w-full overflow-hidden relative">
                        <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->title }}" class="h-full w-full object-cover">
                        <span class="absolute top-3 right-3 bg-white bg-opacity-90 {{ $categoryColor }} text-xs font-semibold px-2 py-1 rounded-full">
                            {{ $category }}
                        </span>
                    </div>
                    @else
                    <div class="h-48 w-full overflow-hidden bg-gradient-to-br {{ $gradient[0] }} {{ $gradient[1] }} flex items-center justify-center relative">
                        <i class="fa-solid fa-image text-6xl text-white opacity-30"></i>
                        <span class="absolute top-3 right-3 bg-white bg-opacity-90 {{ $categoryColor }} text-xs font-semibold px-2 py-1 rounded-full">
                            {{ $category }}
                        </span>
                    </div>
                    @endif
                    <div class="border-b border-gray-100 px-5 py-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-base font-semibold text-gray-900 line-clamp-2">{{ $event->title }}</h3>
                            </div>
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium {{ $badge['class'] }}">
                                {{ $badge['label'] }}
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-1 flex-col justify-between px-5 py-4">
                        <div class="space-y-4">
                            <div class="flex items-start text-sm text-gray-600">
                                <i class="fa-solid fa-location-dot mr-2 mt-0.5 text-[#ff7700]"></i>
                                <span>{{ $location }}</span>
                            </div>
                            <div class="text-sm text-gray-600">
                                <div class="flex items-center">
                                    <i class="fa-solid fa-calendar mr-2 text-[#ff7700]"></i>
                                    <span>{{ $dateDisplay }}</span>
                                </div>
                                @if($timeDisplay)
                                <div class="mt-1 flex items-center">
                                    <i class="fa-regular fa-clock mr-2 text-[#ff7700]"></i>
                                    <span>{{ $timeDisplay }}</span>
                                </div>
                                @endif
                            </div>
                            <div class="rounded-lg bg-gray-50 px-4 py-3 text-sm text-gray-700">
                                <div class="flex items-center justify-between">
                                    <span>Booth types</span>
                                    <span class="font-semibold">{{ $boothTypes }}</span>
                                </div>
                                @if($boothTotal > 0)
                                <div class="mt-2 flex items-center justify-between">
                                    <span>Availability</span>
                                    <span class="font-semibold">{{ $availableBooths }}/{{ $boothTotal }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4 flex flex-wrap items-center gap-2">
                            <a href="{{ route('my-events.show', $event) }}" class="inline-flex items-center rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:border-gray-300 hover:text-gray-900">
                                <i class="fa-regular fa-eye mr-2"></i>
                                View details
                            </a>
                            @if(!in_array($status, ['published', 'ongoing', 'completed']))
                            <a href="{{ route('my-events.edit', $event) }}" class="inline-flex items-center rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:border-gray-300 hover:text-gray-900">
                                <i class="fa-regular fa-pen-to-square mr-2"></i>
                                Edit
                            </a>
                            @endif
                            @if(!in_array($status, ['published', 'ongoing', 'completed']))
                            <form action="{{ route('my-events.destroy', $event) }}" method="POST" class="inline-flex">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center rounded-lg border border-transparent px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50" onclick="return confirm('Delete this event? This action cannot be undone.')">
                                    <i class="fa-regular fa-trash-can mr-2"></i>
                                    Delete
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $events->links() }}
            </div>
            @endif
        </div>
    </div>

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

            // Handle removing individual category filters
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

            // Handle removing individual status filters
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
@include('components.footer')

</html>