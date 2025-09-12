<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Request Details - BoothEase</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@php
// Helper
if (!function_exists('formatRupiah')) {
function formatRupiah($value) {
$digits = preg_replace('/\D/', '', (string) $value);
$num = $digits === '' ? 0 : intval($digits);
return 'Rp' . number_format($num, 0, ',', '.');
}
}

// Mock booking request (based on booking-requests index + book-booth form fields)
$request = [
'id' => 'REQ001',
'booth_number' => 'A01',
'location' => 'Hall 1',
'tenant' => 'Pegasus Peripherals',
'contact_person' => 'Darth Vader',
'phone' => '+62 812-3456-7890',
'price' => 500000,
'request_date' => '2025-09-01',
'status' => 'pending',
'notes' => 'Need power outlet near booth',
// Tenant-submitted booking form fields (from book-booth)
'submitted' => [
'first_name' => 'Darth',
'last_name' => 'Vader',
'business_name' => 'Pegasus Peripherals',
'email' => 'anakin@pegasus.id',
'phone' => '+62 812-3456-7890',
'special_requests' => 'Need power outlet near booth'
]
];
@endphp

<body class="bg-gray-50 min-h-screen font-['Instrument_Sans']">
    @include('components.navbar')

    <!-- Main Content -->
    <div class="min-h-screen py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('components.back-button', ['url' => url('/booking-requests'), 'text' => 'Back to Booking Requests'])

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Booking Request Details</h1>
                <p class="text-gray-600">Request ID: {{ $request['id'] }} • Submitted: {{ date('M d, Y', strtotime($request['request_date'])) }}</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Request Details -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-start mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Request Information</h2>
                        <!-- Status -->
                        <div>
                            @if($request['status'] === 'pending')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">Pending</span>
                            @elseif($request['status'] === 'approved')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Approved</span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">{{ ucfirst($request['status']) }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-6">
                        <!-- Tenant and Contact -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tenant</label>
                                <div class="text-gray-900">{{ $request['tenant'] }}</div>
                            </div>
                        </div>

                        <!-- Booth and Location -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Booth</label>
                                <div class="text-gray-900 font-medium">{{ $request['booth_number'] }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                                <div class="text-gray-900">{{ $request['location'] }}</div>
                            </div>
                        </div>

                        <!-- Submitted At -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Submitted At</label>
                            <div class="text-gray-900">{{ date('Y-m-d H:i:s', strtotime($request['request_date'])) }}</div>
                        </div>

                        <!-- Tenant Submitted Details -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tenant Submitted Details</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs text-gray-500">First Name</label>
                                    <div class="text-gray-900">{{ $request['submitted']['first_name'] }}</div>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500">Last Name</label>
                                    <div class="text-gray-900">{{ $request['submitted']['last_name'] }}</div>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500">Business Name</label>
                                    <div class="text-gray-900">{{ $request['submitted']['business_name'] }}</div>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500">Email</label>
                                    <div class="text-gray-900">{{ $request['submitted']['email'] }}</div>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500">Phone</label>
                                    <div class="text-gray-900">{{ $request['submitted']['phone'] }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Special Requests -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Special Requests</label>
                            <div class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 min-h-[100px]">
                                {{ $request['submitted']['special_requests'] }}
                            </div>
                        </div>

                        <!-- Requested Amount -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Requested Price</label>
                            <span class="text-2xl font-semibold text-[#ff7700]">{{ formatRupiah($request['price']) }}</span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3">
                            <button id="approveBtn" type="button" class="bg-green-500 hover:bg-green-600 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200">
                                Approve Request
                            </button>
                            <button id="rejectBtn" type="button" class="bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200">
                                Reject Request
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Booking Summary -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Booking Summary</h2>

                    <div class="space-y-4 mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $request['tenant'] }}</h3>
                            <p class="text-gray-600">Contact: {{ $request['contact_person'] }}</p>
                            <p class="text-gray-600">Phone: {{ $request['phone'] }}</p>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Booth</span>
                            <span class="font-medium">{{ $request['booth_number'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Location</span>
                            <span class="font-medium">{{ $request['location'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Contact Person</span>
                            <span class="font-medium">{{ $request['contact_person'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Phone</span>
                            <span class="font-medium">{{ $request['phone'] }}</span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="space-y-3">
                        <div class="border-t pt-3">
                            <div class="flex justify-between text-lg font-semibold">
                                <span>Amount Paid</span>
                                <span class="text-[#ff7700]">{{ formatRupiah($request['price']) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                        <p class="text-xs text-orange-700">
                            <strong>Notes:</strong> {{ $request['notes'] }}
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @include('components.footer')

    <script>
        document.getElementById('approveBtn').addEventListener('click', function() {
            if (confirm('Are you sure you want to approve this booking request?')) {
                alert('Booking request approved successfully!');
            }
        });

        document.getElementById('rejectBtn').addEventListener('click', function() {
            if (confirm('Are you sure you want to reject this booking request?')) {
                alert('Booking request rejected successfully!');
            }
        });
    </script>
</body>

</html>