<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tech Innovation Expo 2025 - Event Details</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@php
// Event Data
$event = [
'name' => 'Tech Innovation Expo 2025',
'category' => 'Technology',
'rating' => '5.0',
'reviews' => '50,147',
'availableBooths' => 89,
'totalBooths' => 100,
'description' => 'Join us for the biggest technology innovation expo of the year. Discover cutting-edge technologies, network with industry leaders, and showcase your products to thousands of potential customers. This premier event brings together startups, enterprises, and tech enthusiasts for three days of innovation, networking, and discovery.',
'startDate' => '16 November 2025',
'endDate' => '20 November 2025',
'location' => 'Jakarta Convention Center',
'address' => 'Jl. Gatot Subroto No.1, Jakarta Selatan, DKI Jakarta 12930'
];

// Helper to format rupiah with dot thousand separators
if (!function_exists('formatRupiah')) {
function formatRupiah($value) {
// strip non-digits
$digits = preg_replace('/\D/', '', (string) $value);
$num = $digits === '' ? 0 : intval($digits);
return 'Rp' . number_format($num, 0, ',', '.');
}
}

// Tabs Data
$tabs = [
['name' => 'Booths', 'active' => true],
['name' => 'Details', 'active' => false],
['name' => 'Schedule', 'active' => false],
['name' => 'Organizer', 'active' => false],
['name' => 'Reviews', 'active' => false]
];

// Booths Data
$booths = [
[
'booth' => 'A01',
'size' => '5x4m',
'location' => 'Main Hall - Front',
'price' => '500000',
'status' => 'Booked',
'statusColor' => 'bg-red-100 text-red-800',
'available' => false
],
[
'booth' => 'A02',
'size' => '5x4m',
'location' => 'Main Hall - Front',
'price' => '500000',
'status' => 'Available',
'statusColor' => 'bg-green-100 text-green-800',
'available' => true
],
[
'booth' => 'A03',
'size' => '3x3m',
'location' => 'Main Hall - Middle',
'price' => '350000',
'status' => 'Available',
'statusColor' => 'bg-green-100 text-green-800',
'available' => true
],
[
'booth' => 'B01',
'size' => '4x4m',
'location' => 'Side Hall - Premium',
'price' => '750000',
'status' => 'Available',
'statusColor' => 'bg-green-100 text-green-800',
'available' => true
]
];

$headers = [
['title' => 'Booth', 'class' => 'w-24'],
['title' => 'Size', 'class' => 'w-16'],
['title' => 'Location', 'class' => 'w-40'],
['title' => 'Price', 'class' => 'w-24'],
['title' => 'Status', 'class' => 'w-20'],
['title' => 'Action', 'class' => 'w-28'],
];

// Transform booths data into rows format
$rows = [];
foreach($booths as $booth) {
$rows[] = [
'rowClass' => 'h-20',
'cells' => [
[
'content' => $booth['booth'],
'class' => 'font-medium text-gray-900'
],
[
'content' => $booth['size'],
'class' => 'text-gray-600'
],
[
'content' => $booth['location'],
'class' => 'text-gray-600'
],
[
'content' => formatRupiah($booth['price']),
'class' => 'text-gray-900 font-medium'
],
[
'content' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $booth['statusColor'] . '">' . $booth['status'] . '</span>',
'class' => ''
],
[
'content' => $booth['available']
? '<button class="bg-[#ff7700] hover:bg-orange-600 hover:cursor-pointer text-white text-sm px-4 py-2 rounded-lg transition-colors duration-200">Select</button>'
: '<span class="text-gray-400 text-sm whitespace-nowrap">Not Available</span>',
'class' => ''
]
]
];
}

// Schedule Data
$schedule = [
[
'date' => 'Day 1 - November 16, 2025',
'events' => [
['time' => '08:00 - 09:00', 'title' => 'Registration & Welcome Coffee', 'type' => 'General'],
['time' => '09:00 - 10:30', 'title' => 'Opening Keynote: Future of Technology', 'type' => 'Keynote'],
['time' => '10:45 - 12:00', 'title' => 'AI & Machine Learning Panel', 'type' => 'Panel'],
['time' => '13:00 - 14:00', 'title' => 'Networking Lunch', 'type' => 'Break'],
['time' => '14:00 - 15:30', 'title' => 'Startup Pitch Competition', 'type' => 'Competition']
]
],
[
'date' => 'Day 2 - November 17, 2025',
'events' => [
['time' => '09:00 - 10:30', 'title' => 'Blockchain Innovation Workshop', 'type' => 'Workshop'],
['time' => '10:45 - 12:00', 'title' => 'IoT Solutions Showcase', 'type' => 'Exhibition'],
['time' => '13:00 - 14:00', 'title' => 'Lunch & Networking', 'type' => 'Break'],
['time' => '14:00 - 15:30', 'title' => 'Cybersecurity Panel Discussion', 'type' => 'Panel']
]
]
];

// Organizer Data
$organizer = [
'name' => 'TechExpo Indonesia',
'description' => 'Leading technology event organizer in Southeast Asia, specializing in creating world-class exhibitions and conferences.',
'experience' => '15+ years',
'events' => '200+ events',
'contact' => [
'email' => 'info@techexpo.id',
'phone' => '+62 21 1234 5678',
'website' => 'www.techexpo.id'
]
];

// Reviews Data
$reviews = [
[
'name' => 'Sarah Johnson',
'rating' => 5,
'date' => '2 weeks ago',
'comment' => 'Amazing event! Great networking opportunities and well-organized booths. Definitely worth attending.',
'avatar' => 'SJ'
],
[
'name' => 'Michael Chen',
'rating' => 5,
'date' => '1 month ago',
'comment' => 'Excellent venue and fantastic exhibitors. The organization was top-notch.',
'avatar' => 'MC'
],
[
'name' => 'Amanda Rodriguez',
'rating' => 4,
'date' => '2 months ago',
'comment' => 'Good event overall. The booth prices are reasonable and the location is perfect.',
'avatar' => 'AR'
]
];
@endphp

<body class="bg-gray-50 min-h-screen ">
    <!-- Navbar -->
    @include('components.navbar')

    <!-- Main Content -->
    <div class="min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            @include('components.back-button', ['url' => '/events', 'text' => 'Back to Events'])

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                <!-- Left Column - Event Info -->
                <div class="lg:col-span-3">
                    <!-- Event Header -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                        <div class="h-80 bg-gradient-to-br from-blue-400 to-blue-600 relative">
                            <span class="absolute top-4 right-4 bg-white bg-opacity-90 text-blue-600 text-sm font-semibold px-3 py-1 rounded-full">Technology</span>
                            <!-- <div class="absolute top-4 right-4 mt-10 flex gap-2">
                                <button class="bg-white bg-opacity-90 text-gray-700 p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <button class="bg-white bg-opacity-90 text-gray-700 p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                    <i class="fas fa-share"></i>
                                </button>
                            </div> -->
                        </div>
                        <div class="p-8">
                            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $event['name'] }}</h1>
                            <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-6">
                                <div class="flex items-center">
                                    <i class="fas fa-star mr-2 text-[#ff7700]"></i>
                                    <span>{{ $event['rating'] }} ({{ $event['reviews'] }} Reviews)</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-store mr-2 text-[#ff7700]"></i>
                                    <span>{{ $event['availableBooths'] }} / {{ $event['totalBooths'] }} Booths Available</span>
                                </div>
                            </div>
                            <p class="text-gray-700 leading-relaxed">
                                {{ $event['description'] }}
                            </p>
                        </div>
                    </div>

                    <!-- Event Details Tabs -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        @include('components.tabs', ['tabs' => $tabs, 'onclick' => 'showTab'])

                        <!-- Booths Tab -->
                        <div id="booths" class="tab-content p-6">
                            <div class="mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Available Booths</h3>
                                <p class="text-sm text-gray-600">Select a booth that fits your needs</p>
                            </div>

                            <div class="overflow-x-auto">
                                @include(' components.table', [
                                'headers' => $headers,
                                'rows' => $rows,
                                ])
                            </div>
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
                                                <p class="text-sm text-gray-600">Start Date</p>
                                                <p class="font-medium">{{ $event['startDate'] }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-calendar-alt mr-3 text-[#ff7700]"></i>
                                            <div>
                                                <p class="text-sm text-gray-600">End Date</p>
                                                <p class="font-medium">{{ $event['endDate'] }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-map-marker-alt mr-3 text-[#ff7700]"></i>
                                            <div>
                                                <p class="text-sm text-gray-600">Location</p>
                                                <p class="font-medium">{{ $event['location'] }}</p>
                                                <p class="text-sm text-gray-500">{{ $event['address'] }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-tag mr-3 text-[#ff7700]"></i>
                                            <div>
                                                <p class="text-sm text-gray-600">Category</p>
                                                <p class="font-medium">{{ $event['category'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-2">What's Included</h4>
                                    <ul class="space-y-2">
                                        <li class="flex items-center text-sm">
                                            <i class="fas fa-check text-green-600 mr-2"></i>
                                            Power outlets and WiFi connectivity
                                        </li>
                                        <li class="flex items-center text-sm">
                                            <i class="fas fa-check text-green-600 mr-2"></i>
                                            Basic booth furniture and lighting
                                        </li>
                                        <li class="flex items-center text-sm">
                                            <i class="fas fa-check text-green-600 mr-2"></i>
                                            Event marketing and promotion
                                        </li>
                                        <li class="flex items-center text-sm">
                                            <i class="fas fa-check text-green-600 mr-2"></i>
                                            Access to networking events
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule Tab -->
                        <div id="schedule" class="tab-content p-6 hidden">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Schedule</h3>
                            <div class="space-y-6">
                                @foreach($schedule as $day)
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-3">{{ $day['date'] }}</h4>
                                    <div class="space-y-3">
                                        @foreach($day['events'] as $scheduleEvent)
                                        <div class="flex items-center py-2 px-3 bg-gray-50 rounded-lg">
                                            <div class="text-sm font-medium text-gray-700 w-24 flex-shrink-0">
                                                {{ $scheduleEvent['time'] }}
                                            </div>
                                            <div class="flex-1 ml-4">
                                                <p class="font-medium text-gray-900">{{ $scheduleEvent['title'] }}</p>
                                                <span class="inline-block mt-1 px-2 py-1 text-xs font-medium rounded-full
                                                    @if($scheduleEvent['type'] === 'Keynote'){ bg-blue-100 text-blue-800}
                                                    @elseif($scheduleEvent['type'] === 'Panel'){ bg-green-100 text-green-800}
                                                    @elseif($scheduleEvent['type'] === 'Workshop'){ bg-purple-100 text-purple-800}
                                                    @elseif($scheduleEvent['type'] === 'Break'){ bg-yellow-100 text-yellow-800}
                                                    @else{ bg-gray-100 text-gray-800}
                                                    @endif">
                                                    {{ $scheduleEvent['type'] }}
                                                </span>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Organizer Tab -->
                        <div id="organizer" class="tab-content p-6 hidden">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Organizer</h3>
                            <div class="space-y-6">
                                <div class="flex items-start space-x-4">
                                    <div class="bg-[#ff7700] text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold">
                                        {{ substr($organizer['name'], 0, 1) }}
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-xl font-semibold text-gray-900">{{ $organizer['name'] }}</h4>
                                        <p class="text-gray-600 mt-2">{{ $organizer['description'] }}</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-alt mr-3 text-[#ff7700]"></i>
                                        <div>
                                            <p class="text-sm text-gray-600">Experience</p>
                                            <p class="font-medium">{{ $organizer['experience'] }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-trophy mr-3 text-[#ff7700]"></i>
                                        <div>
                                            <p class="text-sm text-gray-600">Events Organized</p>
                                            <p class="font-medium">{{ $organizer['events'] }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="border-t pt-4">
                                    <h5 class="font-semibold text-gray-900 mb-3">Contact Information</h5>
                                    <div class="space-y-2">
                                        <div class="flex items-center">
                                            <i class="fas fa-envelope mr-3 text-[#ff7700]"></i>
                                            <span class="text-sm">{{ $organizer['contact']['email'] }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-phone mr-3 text-[#ff7700]"></i>
                                            <span class="text-sm">{{ $organizer['contact']['phone'] }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-globe mr-3 text-[#ff7700]"></i>
                                            <span class="text-sm">{{ $organizer['contact']['website'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reviews Tab -->
                        <div id="reviews" class="tab-content p-6 hidden">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-semibold text-gray-900">Reviews ({{ $event['reviews'] }})</h3>
                                <div class="flex items-center">
                                    <div class="flex text-[#ff7700] mr-2">
                                        @for($i = 0; $i < 5; $i++)
                                            <i class="fas fa-star"></i>
                                            @endfor
                                    </div>
                                    <span class="text-sm font-medium">{{ $event['rating'] }}</span>
                                </div>
                            </div>
                            <div class="space-y-4">
                                @foreach($reviews as $review)
                                <div class="border-b border-gray-200 pb-4 last:border-b-0">
                                    <div class="flex items-start space-x-3">
                                        <div class="bg-[#ff7700] text-white w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold">
                                            {{ $review['avatar'] }}
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-1">
                                                <h4 class="font-medium text-gray-900">{{ $review['name'] }}</h4>
                                                <span class="text-sm text-gray-500">{{ $review['date'] }}</span>
                                            </div>
                                            <div class="flex text-[#ff7700] mb-2">
                                                @for($i = 0; $i < $review['rating']; $i++)
                                                    <i class="fas fa-star text-xs"></i>
                                                    @endfor
                                                    @for($i = $review['rating']; $i < 5; $i++)
                                                        <i class="far fa-star text-xs"></i>
                                                        @endfor
                                            </div>
                                            <p class="text-sm text-gray-700">{{ $review['comment'] }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Booking Card -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md p-6  top-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Book Your Booth</h2>

                        <div class="space-y-4">
                            <div class="text-sm text-gray-600">
                                <span>Starting from:</span>
                                <span class="text-xl font-bold text-gray-900 ml-1">{{ formatRupiah(500000) }} - {{ formatRupiah(1000000) }}</span>
                            </div>

                            <!-- Date Selection -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                    <div class="relative">
                                        <input type="text" value="16 November 2025" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" readonly>
                                        <i class="fas fa-calendar-alt absolute right-1.5 top-2.5 text-gray-400"></i>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">08:00</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                                    <div class="relative">
                                        <input type="text" value="20 November 2025" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" readonly>
                                        <i class="fas fa-calendar-alt absolute right-1.5 top-2.5 text-gray-400"></i>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">17:00</div>
                                </div>
                            </div>

                            <!-- Location -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-2 text-[#ff7700]"></i>
                                    <span>Jakarta Convention Center</span>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">Jl. Gatot Subroto No.1, Jakarta Selatan, DKI Jakarta 12930</div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="space-y-2 pt-4">
                                <button class="w-full hover:cursor-pointer bg-[#ff7700] hover:bg-orange-600 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                                    Contact Organizer
                                </button>
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
        @include('components.footer')
</body>

</html>