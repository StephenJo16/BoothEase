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

        <!-- Events Grid -->
        <section class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Section 1: Open for Registration -->
                @if($openForRegistration->count() > 0)
                <div class="mb-12">
                    <div class="flex items-center mb-6">
                        <i class="fas fa-calendar-check text-3xl text-green-600 mr-3"></i>
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
                            <div class="h-48 bg-gradient-to-br {{ $gradient[0] }} {{ $gradient[1] }} relative">
                                <span class="absolute top-3 left-3 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded-full">
                                    <i class="fas fa-circle-check mr-1"></i> Open
                                </span>
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
                                <a href="{{ route('events.show', $event->id) }}" class="block w-full bg-[#ff7700] hover:bg-[#e66600] text-white text-sm py-2 px-3 rounded-lg transition-colors duration-200 text-center">
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
                            <div class="h-48 bg-gradient-to-br {{ $gradient[0] }} {{ $gradient[1] }} relative">
                                <span class="absolute top-3 left-3 bg-orange-500 text-white text-xs font-semibold px-2 py-1 rounded-full">
                                    <i class="fas fa-lock mr-1"></i> Closed
                                </span>
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
                        <i class="fas fa-circle-notch fa-spin text-3xl text-blue-600 mr-3"></i>
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
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 relative border-2 border-blue-500">
                            <div class="h-48 bg-gradient-to-br {{ $gradient[0] }} {{ $gradient[1] }} relative">
                                <span class="absolute top-3 left-3 bg-blue-500 text-white text-xs font-semibold px-2 py-1 rounded-full animate-pulse">
                                    <i class="fas fa-play-circle mr-1"></i> Live Now
                                </span>
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
                                <a href="{{ route('events.show', $event->id) }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-sm py-2 px-3 rounded-lg transition-colors duration-200 text-center">
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
                            <div class="h-48 bg-gradient-to-br {{ $gradient[0] }} {{ $gradient[1] }} relative grayscale">
                                <span class="absolute top-3 left-3 bg-gray-600 text-white text-xs font-semibold px-2 py-1 rounded-full">
                                    <i class="fas fa-flag-checkered mr-1"></i> Completed
                                </span>
                                <span class="absolute top-3 right-3 bg-white bg-opacity-90 {{ $categoryColor }} text-xs font-semibold px-2 py-1 rounded-full">
                                    {{ $event->category->name ?? 'Uncategorized' }}
                                </span>
                            </div>
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
                                <a href="{{ route('events.show', $event->id) }}" class="block w-full bg-gray-400 hover:bg-gray-500 text-white text-sm py-2 px-3 rounded-lg transition-colors duration-200 text-center">
                                    View Details
                                </a>
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
</body>

</html>