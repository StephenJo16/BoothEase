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

// Helper to get minimum booth price
if (!function_exists('getMinBoothPrice')) {
function getMinBoothPrice($event) {
$prices = [];
$boothConfig = $event->location['booths'] ?? [];

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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row gap-4 items-center justify-center">
                <div class="w-full sm:w-96">
                    @include('components.search-bar', ['placeholder' => 'Search events...'])
                </div>

                <div class="w-full sm:w-auto">
                    @include('components.filter-button', ['label' => 'Filter'])
                </div>
            </div>
        </div>

        <!-- Events Grid -->
        <section class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @if($events->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($events as $event)
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

                    // Use category id to pick a consistent color scheme
                    $colorIndex = ($event->category_id ?? 0) % count($categoryColors);
                    $gradientIndex = ($event->category_id ?? 0) % count($gradients);

                    $categoryColor = $categoryColors[$colorIndex];
                    $gradient = $gradients[$gradientIndex];

                    $minPrice = getMinBoothPrice($event);
                    @endphp
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 relative">
                        <div class="h-48 bg-gradient-to-br {{ $gradient[0] }} {{ $gradient[1] }} relative">
                            <span class="absolute top-3 right-3 bg-white bg-opacity-90 {{ $categoryColor }} text-xs font-semibold px-2 py-1 rounded-full">
                                {{ $event->category->name ?? 'Uncategorized' }}
                            </span>
                        </div>
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
                            <a href="{{ route('eventdetails', $event->id) }}" class="block w-full bg-[#ff7700] hover:bg-[#e66600] text-white text-sm py-2 px-3 rounded-lg transition-colors duration-200 text-center">
                                View Details
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No Events Available</h3>
                    <p class="text-gray-500">There are currently no published events. Please check back later.</p>
                </div>
                @endif

                <!-- Pagination -->
                @if($events->hasPages())
                <div class="mt-8">
                    {{ $events->links() }}
                </div>
                @endif
            </div>
        </section>
    </div>

    <!-- Footer -->
    @include('components.footer')
</body>

</html>