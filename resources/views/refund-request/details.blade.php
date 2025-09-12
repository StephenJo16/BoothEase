<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Refund Request Details - BoothEase</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@php
// Reuse formatter
if (!function_exists('formatRupiah')) {
function formatRupiah($value) {
$digits = preg_replace('/\D/', '', (string) $value);
$num = $digits === '' ? 0 : intval($digits);
return 'Rp' . number_format($num, 0, ',', '.');
}
}

// Mocked refund request data for REQ-0001 (Pegasus Peripherals)
$refund = [
'id' => 1,
'user_id' => 42,
'booking_id' => 'BKG-1001',
'tenant' => 'Pegasus Peripherals',
'reason' => 'Event rescheduled, cannot attend',
'amount' => 500000,
'status' => 'pending',
'created_at' => '2025-10-15 09:12:00',
'updated_at' => null,
];

// Mock booking details (could be joined in real app)
$booking = [
'id' => $refund['booking_id'],
'event' => 'Tech Innovation Expo 2025',
'venue' => 'Jakarta Convention Center',
'dates' => '20 - 28 October 2025'
];
@endphp

<body class="bg-gray-50 min-h-screen font-['Instrument_Sans']">
    @include('components.navbar')

    <!-- Main Content -->
    <div class="min-h-screen py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('components.back-button', ['url' => url('/refund-request'), 'text' => 'Back to Refund Requests'])

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Refund Request Details</h1>
                <p class="text-gray-600">Request ID: REQ-{{ str_pad($refund['id'], 4, '0', STR_PAD_LEFT) }}</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Refund Request Details -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Request Information</h2>
                        <!-- Status -->
                        <div>
                            <div>
                                @if($refund['status'] === 'pending')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">Pending</span>
                                @elseif($refund['status'] === 'approved')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Approved</span>
                                @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">{{ ucfirst($refund['status']) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>



                    <div class="space-y-6">


                        <!-- Tenant and Booking ID -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tenant</label>
                                {{ $refund['tenant'] }}
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Booking ID</label>
                                {{ $refund['booking_id'] }}
                            </div>
                        </div>

                        <!-- Submitted At -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Submitted At</label>
                            {{ $refund['created_at'] }}
                        </div>

                        <!-- Refund Reason -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Refund Reason</label>
                            <div class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 min-h-[100px]">
                                {{ $refund['reason'] }}
                            </div>
                        </div>

                        <!-- Requested Amount -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Requested Amount</label>
                            <span class="text-2xl font-semibold text-[#ff7700]">{{ formatRupiah($refund['amount']) }}</span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3">
                            <button type="button" class="bg-green-500 hover:bg-green-600 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200">
                                Approve Request
                            </button>
                            <button type="button" class="bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200">
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
                            <h3 class="text-lg font-semibold text-gray-900">{{ $booking['event'] }}</h3>
                            <p class="text-gray-600">{{ $booking['venue'] }}</p>
                            <p class="text-gray-600">{{ $booking['dates'] }}</p>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Booking ID</span>
                            <span class="font-medium">{{ $booking['id'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Event</span>
                            <span class="font-medium">{{ $booking['event'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Venue</span>
                            <span class="font-medium">{{ $booking['venue'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Dates</span>
                            <span class="font-medium">{{ $booking['dates'] }}</span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="space-y-3">
                        <div class="border-t pt-3">
                            <div class="flex justify-between text-lg font-semibold">
                                <span>Original Paid Amount</span>
                                <span class="text-[#ff7700]">{{ formatRupiah($refund['amount']) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                        <p class="text-xs text-orange-700">
                            * Refund amount subject to processing fees and refund policy terms
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @include('components.footer')

    <script>
        // Action button functionality
        document.querySelector('.bg-green-500').addEventListener('click', function() {
            if (confirm('Are you sure you want to approve this refund request?')) {
                alert('Refund request approved successfully!');
            }
        });

        document.querySelector('.bg-red-500').addEventListener('click', function() {
            if (confirm('Are you sure you want to reject this refund request?')) {
                alert('Refund request rejected successfully!');
            }
        });
    </script>
</body>

</html>