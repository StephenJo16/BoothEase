<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $event->title }} - Event Details</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@php

// Helper to get color scheme based on category
if (!function_exists('getCategoryColors')) {
function getCategoryColors($categoryId) {
$schemes = [
['color' => 'text-blue-600', 'gradient' => ['from-blue-400', 'to-blue-600']],
['color' => 'text-green-600', 'gradient' => ['from-green-400', 'to-green-600']],
['color' => 'text-purple-600', 'gradient' => ['from-purple-400', 'to-purple-600']],
['color' => 'text-red-600', 'gradient' => ['from-red-400', 'to-red-600']],
['color' => 'text-orange-600', 'gradient' => ['from-yellow-400', 'to-orange-500']],
['color' => 'text-indigo-600', 'gradient' => ['from-indigo-400', 'to-indigo-600']],
['color' => 'text-pink-600', 'gradient' => ['from-pink-400', 'to-pink-600']],
['color' => 'text-teal-600', 'gradient' => ['from-teal-400', 'to-teal-600']],
['color' => 'text-cyan-600', 'gradient' => ['from-cyan-400', 'to-cyan-600']],
];
$index = ($categoryId ?? 0) % count($schemes);
return $schemes[$index];
}
}

$colorScheme = getCategoryColors($event->category_id);

// Format event dates and times using helper functions
$dateDisplay = formatEventDate($event);
$timeDisplay = formatEventTime($event);

// Tabs Data
$tabs = [
['name' => 'Booths', 'active' => true],
['name' => 'Details', 'active' => false],
['name' => 'Organizer', 'active' => false]
];

// Define table headers
$headers = [
['title' => 'Booth', 'class' => 'w-24'],
['title' => 'Size', 'class' => 'w-16'],
['title' => 'Type', 'class' => 'w-32'],
['title' => 'Price', 'class' => 'w-24'],
['title' => 'Status', 'class' => 'w-20'],
];

// Transform booths data into rows format
$rows = [];
foreach($event->booths as $booth) {
$boothStatus = getBoothStatusDisplay($booth->status);

$rows[] = [
'rowClass' => 'h-20',
'cells' => [
[
'content' => $booth->number ?? 'N/A',
'class' => 'font-medium text-gray-900'
],
[
'content' => $booth->size ?? 'N/A',
'class' => 'text-gray-600'
],
[
'content' => ucfirst($booth->type ?? 'Standard'),
'class' => 'text-gray-600'
],
[
'content' => formatRupiah($booth->price),
'class' => 'text-gray-900 font-medium'
],
[
'content' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $boothStatus['class'] . '">' . $boothStatus['label'] . '</span>',
'class' => ''
],
]
];
}
@endphp

<body class="bg-gray-50">

    <body class="bg-gray-50 m-0 min-h-screen flex flex-col">
        <!-- Navbar -->
        @include('components.navbar')

        <!-- Main Content -->
        <div class="pt-8 pb-0">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Back Button -->
                @include('components.back-button', ['url' => '/events', 'text' => 'Back to Events'])

                <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                    <!-- Left Column - Event Info -->
                    <div class="lg:col-span-3">
                        <!-- Event Header -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                            <div class="h-80 bg-gradient-to-br {{ $colorScheme['gradient'][0] }} {{ $colorScheme['gradient'][1] }} relative">
                                <span class="absolute top-4 right-4 bg-white bg-opacity-90 {{ $colorScheme['color'] }} text-sm font-semibold px-3 py-1 rounded-full">{{ $event->category->name ?? 'Uncategorized' }}</span>
                            </div>
                            <div class="p-8">
                                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $event->title }}</h1>
                                <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-6">
                                    <div class="flex items-center">
                                        <i class="fas fa-store mr-2 text-[#ff7700]"></i>
                                        <span>{{ $availableBooths }} / {{ $totalBooths }} Booths Available</span>
                                    </div>
                                </div>
                                <p class="text-gray-700 leading-relaxed">
                                    {{ $event->description ?? 'No description available for this event.' }}
                                </p>
                            </div>
                        </div>

                        <!-- Booking Card (Mobile) -->
                        <div class="lg:hidden mt-6 mb-8">
                            <div class="bg-white rounded-lg shadow-md p-6">
                                <h2 class="text-xl font-semibold text-gray-900 mb-4">Book Your Booth</h2>

                                <div class="space-y-4">
                                    @if($minPrice > 0)
                                    <div class="text-sm text-gray-600">
                                        <span>Starting from:</span>
                                        <span class="text-xl font-bold text-gray-900 ml-1">
                                            {{ formatRupiah($minPrice) }}
                                            @if($maxPrice > $minPrice)
                                            - {{ formatRupiah($maxPrice) }}
                                            @endif
                                        </span>
                                    </div>
                                    @endif

                                    <!-- Date and Time -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                                        <div class="relative">
                                            <input type="text" value="{{ $dateDisplay }}" class="w-full py-2 text-sm" readonly>
                                            <i class="fas fa-calendar-alt absolute right-1.5 top-2.5 text-gray-400"></i>
                                        </div>
                                    </div>

                                    @if($timeDisplay)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Time</label>
                                        <div class="relative">
                                            <input type="text" value="{{ $timeDisplay }}" class="w-full py-2 text-sm" readonly>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Location -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-map-marker-alt mr-2 text-[#ff7700]"></i>
                                            <span>{{ $event->venue ?? 'Venue TBA' }}</span>
                                        </div>
                                        @if($event->address)
                                        <div class="text-xs text-gray-500 mt-1">{{ $event->address }}</div>
                                        @endif
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="space-y-2 pt-4">
                                        <button class="w-full hover:cursor-pointer bg-[#ff7700] hover:bg-[#e66600] text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                                            Contact Organizer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Event Details Tabs -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            @include('components.tabs', ['tabs' => $tabs, 'onclick' => 'showTab'])

                            <!-- Booths Tab -->
                            <div id="booths" class="tab-content p-6">
                                <div class="mb-4 flex justify-between items-center">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Available Booths</h3>
                                        <p class="text-sm text-gray-600">Select a booth that fits your needs</p>
                                    </div>
                                    @if($isRegistrationOpen)
                                    <a href="/events/{{ $event->id }}/booths" class="bg-[#ff7700] hover:bg-[#e66600] text-white font-medium py-2 px-6 rounded-lg transition-colors duration-200">
                                        View Booths
                                    </a>
                                    @else
                                    <div class="bg-gray-100 text-gray-600 font-medium py-2 px-6 rounded-lg">
                                        Registration Closed
                                    </div>
                                    @endif
                                </div>

                                @if($event->booths->count() > 0)
                                <div class="overflow-x-auto">
                                    @include('components.table', [
                                    'headers' => $headers,
                                    'rows' => $rows,
                                    ])
                                </div>
                                @else
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-store text-4xl mb-2"></i>
                                    <p>No booths available for this event yet.</p>
                                </div>
                                @endif
                            </div>

                            <!-- Details Tab -->
                            <div id="details" class="tab-content p-6 hidden">
                                <div class="space-y-6">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Event Information</h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="flex items-center">
                                                <i class="fas fa-calendar-alt mr-3 text-[#ff7700]"></i>
                                                <div>
                                                    <p class="text-sm text-gray-600">Date</p>
                                                    <p class="font-medium">{{ $dateDisplay }}</p>
                                                </div>
                                            </div>
                                            @if($timeDisplay)
                                            <div class="flex items-center">
                                                <i class="fas fa-clock mr-3 text-[#ff7700]"></i>
                                                <div>
                                                    <p class="text-sm text-gray-600">Time</p>
                                                    <p class="font-medium">{{ $timeDisplay }}</p>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="flex items-center">
                                                <i class="fas fa-map-marker-alt mr-3 text-[#ff7700]"></i>
                                                <div>
                                                    <p class="text-sm text-gray-600">Location</p>
                                                    <p class="font-medium">{{ $event->venue ?? 'Venue TBA' }}</p>
                                                    @if($event->city)
                                                    <p class="text-sm text-gray-500">{{ $event->city }}</p>
                                                    @endif
                                                    @if($event->address)
                                                    <p class="text-xs text-gray-500">{{ $event->address }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-tag mr-3 text-[#ff7700]"></i>
                                                <div>
                                                    <p class="text-sm text-gray-600">Category</p>
                                                    <p class="font-medium">{{ $event->category->name ?? 'Uncategorized' }}</p>
                                                </div>
                                            </div>
                                            @if($event->capacity)
                                            <div class="flex items-center">
                                                <i class="fas fa-users mr-3 text-[#ff7700]"></i>
                                                <div>
                                                    <p class="text-sm text-gray-600">Capacity</p>
                                                    <p class="font-medium">{{ number_format($event->capacity) }} tenants</p>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- Organizer Tab -->
                            <div id="organizer" class="tab-content p-6 hidden">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Organizer</h3>
                                <div class="space-y-6">
                                    <div class="flex items-start space-x-4">
                                        <div class="bg-[#ff7700] text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold">
                                            {{ strtoupper(substr($event->user->name ?? 'O', 0, 1)) }}
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <h4 class="text-xl font-semibold text-gray-900">{{ $event->user->name ?? 'Anonymous Organizer' }}</h4>
                                                @if($averageRating > 0)
                                                <div class="flex items-center">
                                                    <div class="flex text-[#ff7700] mr-1">
                                                        @for($i = 0; $i < 5; $i++)
                                                            @if($i < floor($averageRating))
                                                            <i class="fas fa-star text-sm"></i>
                                                            @elseif($i < $averageRating)
                                                                <i class="fas fa-star-half-alt text-sm"></i>
                                                                @else
                                                                <i class="far fa-star text-sm"></i>
                                                                @endif
                                                                @endfor
                                                    </div>
                                                    <span class="text-sm font-medium text-gray-700">{{ $averageRating }}</span>
                                                    <span class="text-sm text-gray-500 ml-1">({{ number_format($totalReviews) }})</span>
                                                </div>
                                                @endif
                                            </div>
                                            @if($event->user->email)
                                            <p class="text-gray-600 mt-2">
                                                <i class="fas fa-envelope mr-2 text-[#ff7700]"></i>
                                                {{ $event->user->email }}
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="border-t pt-4">
                                        <h5 class="font-semibold text-gray-900 mb-3">About this Organizer</h5>
                                        <!-- Statistics Grid -->
                                        <div class="grid grid-cols-2 gap-4 mb-4">
                                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                                <div class="text-2xl font-bold text-[#ff7700]">{{ $completedEvents ?? 0 }}</div>
                                                <div class="text-sm text-gray-600 mt-1">Events Completed</div>
                                            </div>
                                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                                <div class="text-2xl font-bold text-[#ff7700]">{{ $completedBookings ?? 0 }}</div>
                                                <div class="text-sm text-gray-600 mt-1">Completed Bookings</div>
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-600">
                                            This event is organized by {{ $event->user->name ?? 'the event organizer' }}.
                                            For any inquiries or questions about this event, please use the contact button below.
                                        </p>

                                        @if($organizerRatings->count() > 0)
                                        <div class="mt-6">
                                            <h5 class="font-semibold text-gray-900 mb-3">Reviews ({{ number_format($totalReviews) }})</h5>
                                            <div class="space-y-4">
                                                @foreach($organizerRatings as $rating)
                                                <div class="border-b border-gray-200 pb-4 last:border-b-0">
                                                    <div class="flex items-start space-x-3">
                                                        <div class="bg-[#ff7700] text-white w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold">
                                                            {{ strtoupper(substr($rating->rater->name ?? 'A', 0, 1)) }}{{ strtoupper(substr(explode(' ', $rating->rater->name ?? 'N')[1] ?? '', 0, 1)) }}
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="flex items-center justify-between mb-1">
                                                                <div>
                                                                    <h4 class="font-medium text-gray-900">{{ $rating->rater->name ?? 'Anonymous' }}</h4>
                                                                    @if($rating->event_id !== $event->id && $rating->event)
                                                                    <p class="text-xs text-gray-500">from event: {{ $rating->event->title }}</p>
                                                                    @endif
                                                                </div>
                                                                <span class="text-sm text-gray-500">{{ $rating->created_at->diffForHumans() }}</span>
                                                            </div>
                                                            <div class="flex text-[#ff7700] mb-2">
                                                                @for($i = 0; $i < $rating->rating; $i++)
                                                                    <i class="fas fa-star text-xs"></i>
                                                                    @endfor
                                                                    @for($i = $rating->rating; $i < 5; $i++)
                                                                        <i class="far fa-star text-xs"></i>
                                                                        @endfor
                                                            </div>
                                                            @if($rating->feedback)
                                                            <p class="text-sm text-gray-700">{{ $rating->feedback }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Booking Card -->
                    <div class="lg:col-span-2 hidden lg:block">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Book Your Booth</h2>

                            <div class="space-y-4">
                                @if($minPrice > 0)
                                <div class="text-sm text-gray-600">
                                    <span>Starting from:</span>
                                    <span class="text-xl font-bold text-gray-900 ml-1">
                                        {{ formatRupiah($minPrice) }}
                                        @if($maxPrice > $minPrice)
                                        - {{ formatRupiah($maxPrice) }}
                                        @endif
                                    </span>
                                </div>
                                @endif

                                <!-- Date and Time -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                                    <div class="relative">
                                        <input type="text" value="{{ $dateDisplay }}" class="w-full py-2 rounded-lg text-sm" readonly>
                                    </div>
                                </div>

                                @if($timeDisplay)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Time</label>
                                    <div class="relative">
                                        <input type="text" value="{{ $timeDisplay }}" class="w-full py-2 rounded-lg text-sm" readonly>
                                    </div>
                                </div>
                                @endif

                                <!-- Location -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-map-marker-alt mr-2 text-[#ff7700]"></i>
                                        <span>{{ $event->venue ?? 'Venue TBA' }}</span>
                                    </div>
                                    @if($event->address)
                                    <div class="text-xs text-gray-500 mt-1">{{ $event->address }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <script>
            function showTab(tabName) {
                // Hide all tab contents
                const tabContents = document.querySelectorAll('.tab-content');
                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });

                // Remove active styles from all tab buttons
                const tabButtons = document.querySelectorAll('nav button');
                tabButtons.forEach(button => {
                    button.classList.remove('bg-[#ff7700]', 'text-white', 'border-[#ff7700]');
                    button.classList.add('text-gray-600', 'hover:text-gray-800', 'border-transparent', 'hover:border-gray-300');
                });

                // Show the selected tab content
                const selectedTab = document.getElementById(tabName);
                if (selectedTab) {
                    selectedTab.classList.remove('hidden');
                }

                // Add active styles to the clicked button
                const clickedButton = event.target;
                clickedButton.classList.remove('text-gray-600', 'hover:text-gray-800', 'border-transparent', 'hover:border-gray-300');
                clickedButton.classList.add('bg-[#ff7700]', 'text-white', 'border-[#ff7700]');
            }
        </script>
        <!-- Footer -->
        <div class="mt-12">@include('components.footer')</div>

    </body>

</html>