<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Details - BoothEase</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
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
@endphp

<body class="bg-gray-50 min-h-screen font-['Instrument_Sans']">
    <!-- Navbar -->
    @include('components.navbar')

    <!-- Main Content -->
    <div class="min-h-screen py-8">

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Back Button -->
            @include('components.back-button', ['text' => 'Back to My Bookings', 'url' => route('my-bookings')])
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Booking Details</h1>
                        <p class="text-gray-600">Booking ID: ID-618261</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-2"></i>
                            Approved
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Event Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Event Information</h2>
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">Tech Innovation Expo 2025</h3>
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-map-marker-alt mr-3 text-[#ff7700]"></i>
                                    <span class="text-gray-700">Jakarta Convention Center</span>
                                </div>
                                <div class="flex items-center mb-4">
                                    <i class="fas fa-calendar-alt mr-3 text-[#ff7700]"></i>
                                    <span class="text-gray-700">20 - 28 September 2025</span>
                                </div>
                                <p class="text-gray-600 leading-relaxed">
                                    Join the largest technology innovation expo in Southeast Asia. This premier event brings together tech innovators, startups, and industry leaders to showcase cutting-edge solutions and networking opportunities.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Booth Details -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Booth Details</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Booth Number</h4>
                                <p class="text-lg font-semibold text-gray-900">A01</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Booth Size</h4>
                                <p class="text-lg font-semibold text-gray-900">3m × 3m</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Location</h4>
                                <p class="text-lg font-semibold text-gray-900">Hall A, Ground Floor</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Category</h4>
                                <p class="text-lg font-semibold text-gray-900">Technology & Innovation</p>
                            </div>
                        </div>

                        <!-- Included Features -->
                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Included Features</h4>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="flex items-center">
                                    <i class="fas fa-plug text-[#ff7700] mr-2"></i>
                                    <span class="text-sm text-gray-700">Power Outlet</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-wifi text-[#ff7700] mr-2"></i>
                                    <span class="text-sm text-gray-700">WiFi</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-box text-[#ff7700] mr-2"></i>
                                    <span class="text-sm text-gray-700">Storage Space</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-tv text-[#ff7700] mr-2"></i>
                                    <span class="text-sm text-gray-700">Display Wall</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booth Layout -->
                    <!-- <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Booth Layout</h2>
                        <div class="bg-gray-100 rounded-lg p-8 text-center">
                            <div class="bg-white border-2 border-[#ff7700] rounded-lg p-6 inline-block">
                                <div class="text-[#ff7700] text-4xl mb-2">
                                    <i class="fas fa-store"></i>
                                </div>
                                <div class="text-lg font-semibold text-gray-900">Booth A01</div>
                                <div class="text-sm text-gray-600">3m × 3m</div>
                            </div>
                            <p class="text-sm text-gray-600 mt-4">Hall A - Ground Floor Layout</p>
                        </div>
                    </div> -->

                    <!-- Contact Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Event Organizer Contact</h2>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-[#ff7700] mr-3"></i>
                                <span class="text-gray-700">info@techinnovationexpo.com</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-phone text-[#ff7700] mr-3"></i>
                                <span class="text-gray-700">+62 21 1234 5678</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-globe text-[#ff7700] mr-3"></i>
                                <span class="text-gray-700">www.techinnovationexpo.com</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Booking Summary -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Booking Summary</h2>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Booking Date</span>
                                <span class="font-medium">18-10-2025</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Duration</span>
                                <span class="font-medium">9 Days</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Booth Type</span>
                                <span class="font-medium">Standard</span>
                            </div>
                            <div class="border-t pt-4">
                                <div class="flex justify-between text-lg font-semibold">
                                    <span>Total Amount</span>
                                    <span class="text-[#ff7700]">{{ formatRupiah(500000) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Payment Details</h2>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Method</span>
                                <span class="font-medium">GoPay</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Status</span>
                                <span class="text-green-600 font-medium">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Paid
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Transaction ID</span>
                                <span class="font-medium text-sm">TXN-789456123</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Date</span>
                                <span class="font-medium">18-10-2025</span>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Timeline -->
                    <!-- <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Booking Timeline</h2>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-check text-green-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Booking Approved</p>
                                    <p class="text-xs text-gray-500">18-10-2025, 14:30</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-credit-card text-green-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Payment Completed</p>
                                    <p class="text-xs text-gray-500">18-10-2025, 14:15</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-plus text-blue-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Booking Created</p>
                                    <p class="text-xs text-gray-500">18-10-2025, 14:00</p>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <button class="mb-2 w-full bg-[#ff7700] hover:bg-orange-600 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                            <i class="fas fa-download mr-2"></i>
                            Download Invoice
                        </button>
                        <a href="{{ route('request-refund') }}">
                            <button class="w-full bg-red-50 hover:bg-red-100 text-red-600 font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                                <i class="fas fa-undo mr-2"></i>
                                Request Refund
                            </button>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Important Notes -->
            <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-yellow-800 mb-2">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Important Notes
                </h3>
                <ul class="text-sm text-yellow-700 space-y-1">
                    <li>• Please arrive at least 2 hours before the event starts for booth setup</li>
                    <li>• Booth setup materials and decorations must comply with venue regulations</li>
                    <li>• Event confirmation and access passes will be sent 48 hours before the event</li>
                    <li>• Cancellation requests must be made at least 7 days before the event date</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('components.footer')
</body>

</html>