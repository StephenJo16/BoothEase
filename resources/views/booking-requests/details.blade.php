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

if (!function_exists('formatPhoneNumber')) {
function formatPhoneNumber($number) {
$digits = preg_replace('/\D+/', '', (string) $number);
if ($digits === '') {
return $number;
}

if (substr($digits, 0, 2) === '62') {
$country = '+62';
$rest = substr($digits, 2);
} elseif (substr($digits, 0, 1) === '0') {
$country = '+62';
$rest = ltrim($digits, '0');
} else {
return $number;
}

if ($rest === '') {
return $country;
}

if (strlen($rest) <= 3) {
$formattedRest = $rest;
} else {
$firstBlock = substr($rest, 0, 3);
$remaining = substr($rest, 3);
$chunks = str_split($remaining, 4);
$formattedRest = $firstBlock . ($chunks ? '-' . implode('-', $chunks) : '');
}

return trim($country . ' ' . $formattedRest);
}
}

// Status display mapping
$statusDisplay = [
'pending' => ['label' => 'Pending', 'class' => 'bg-yellow-100 text-yellow-800'],
'confirmed' => ['label' => 'Approved', 'class' => 'bg-green-100 text-green-800'],
'rejected' => ['label' => 'Rejected', 'class' => 'bg-red-100 text-red-800'],
'cancelled' => ['label' => 'Cancelled', 'class' => 'bg-gray-100 text-gray-800']
];

$status = $statusDisplay[$booking->status] ?? ['label' => ucfirst($booking->status), 'class' => 'bg-gray-100 text-gray-800'];
@endphp

<body class="bg-gray-50 min-h-screen font-['Instrument_Sans']">
    @include('components.navbar')

    <!-- Main Content -->
    <div class="min-h-screen py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('components.back-button', ['url' => route('booking-requests', ['event' => $event->id]), 'text' => 'Back to Booking Requests'])

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Booking Request Details</h1>
                <p class="text-gray-600">Request ID: REQ{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }} • Submitted: {{ $booking->created_at->format('M d, Y') }}</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Request Details -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-start mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Request Information</h2>
                        <!-- Status -->
                        <div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $status['class'] }}">{{ $status['label'] }}</span>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <!-- Tenant and Contact -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tenant</label>
                                <div class="text-gray-900">{{ $booking->user->name ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <!-- Booth and Event -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Booth</label>
                                <div class="text-gray-900 font-medium">{{ $booking->booth->number ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Booth Type</label>
                                <div class="text-gray-900">{{ ucfirst($booking->booth->type ?? 'N/A') }}</div>
                            </div>
                        </div>

                        <!-- Submitted At -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Submitted At</label>
                            <div class="text-gray-900">{{ $booking->created_at->format('Y-m-d H:i:s') }}</div>
                        </div>

                        <!-- Tenant Submitted Details -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tenant Contact Details</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs text-gray-500">Contact Name</label>
                                    <div class="text-gray-900">{{ $booking->user->display_name ?? 'N/A' }}</div>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500">Business Name</label>
                                    <div class="text-gray-900">{{ $booking->user->name ?? 'N/A' }}</div>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500">Email</label>
                                    <div class="text-gray-900">{{ $booking->user->email ?? 'N/A' }}</div>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500">Phone</label>
                                    <div class="text-gray-900">{{ $booking->user && $booking->user->phone_number ? formatPhoneNumber($booking->user->phone_number) : 'N/A' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Special Requests -->
                        @if($booking->notes)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Special Requests / Notes</label>
                            <div class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 min-h-[100px]">
                                {{ $booking->notes }}
                            </div>
                        </div>
                        @endif

                        <!-- Requested Amount -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Booking Price</label>
                            <span class="text-2xl font-semibold text-[#ff7700]">{{ formatRupiah($booking->total_price) }}</span>
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
                            <h3 class="text-lg font-semibold text-gray-900">{{ $booking->user->name ?? 'N/A' }}</h3>
                            <p class="text-gray-600">Contact: {{ $booking->user->display_name ?? 'N/A' }}</p>
                            <p class="text-gray-600">Phone: {{ $booking->user && $booking->user->phone_number ? formatPhoneNumber($booking->user->phone_number) : 'N/A' }}</p>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Booth</span>
                            <span class="font-medium">{{ $booking->booth->number ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Booth Type</span>
                            <span class="font-medium">{{ ucfirst($booking->booth->type ?? 'N/A') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Contact Person</span>
                            <span class="font-medium">{{ $booking->user->display_name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Email</span>
                            <span class="font-medium">{{ $booking->user->email ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="space-y-3">
                        <div class="border-t pt-3">
                            <div class="flex justify-between text-lg font-semibold">
                                <span>Booking Amount</span>
                                <span class="text-[#ff7700]">{{ formatRupiah($booking->total_price) }}</span>
                            </div>
                        </div>
                    </div>

                    @if($booking->notes)
                    <div class="mt-6 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                        <p class="text-xs text-orange-700">
                            <strong>Notes:</strong> {{ $booking->notes }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    @include('components.footer')

    <script>
        document.getElementById('approveBtn')?.addEventListener('click', function() {
            if (confirm('Are you sure you want to approve this booking request?')) {
                alert('Booking request approved successfully!');
            }
        });

        document.getElementById('rejectBtn')?.addEventListener('click', function() {
            if (confirm('Are you sure you want to reject this booking request?')) {
                alert('Booking request rejected successfully!');
            }
        });
    </script>
</body>

</html>
