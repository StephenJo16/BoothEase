<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment - BoothEase</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Midtrans Snap -->
    <script type="text/javascript"
        src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
</head>

@php
// Helper to format rupiah
if (!function_exists('formatRupiah')) {
function formatRupiah($value) {
$digits = preg_replace('/\D/', '', (string) $value);
$num = $digits === '' ? 0 : intval($digits);
return 'Rp' . number_format($num, 0, ',', '.');
}
}

$event = $booking->booth->event;
$booth = $booking->booth;
@endphp

<body class="bg-gray-50 min-h-screen font-['Instrument_Sans']">
    <!-- Navbar -->
    @include('components.navbar')

    <!-- Main Content -->
    <div class="min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            @include('components.back-button', ['text' => 'Back to Booking Details', 'url' => route('my-booking-details', $booking->id)])

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Complete Your Payment</h1>
                <p class="text-gray-600">Booking ID: ID-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Payment Information -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Payment Information</h2>

                        <!-- Booking Summary -->
                        <div class="border-b pb-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Booking Details</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Event</span>
                                    <span class="font-medium text-right">{{ $event->title }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Booth Number</span>
                                    <span class="font-medium">{{ $booth->number }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Booth Size</span>
                                    <span class="font-medium">{{ $booth->size ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Booking Date</span>
                                    <span class="font-medium">{{ $booking->booking_date->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method Info -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Method</h3>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                                    <div>
                                        <p class="text-blue-900 font-medium mb-1">Secure Payment Gateway</p>
                                        <p class="text-blue-800 text-sm">
                                            You will be redirected to our secure payment partner (Midtrans) to complete your payment.
                                            Multiple payment methods are available including credit card, bank transfer, e-wallet, and more.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Button -->
                        <button id="pay-button"
                            class="w-full bg-[#ff7700] hover:bg-[#e66600] text-white font-semibold py-4 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center">
                            <i class="fas fa-lock mr-2"></i>
                            Proceed to Secure Payment
                        </button>

                        <!-- Security Info -->
                        <div class="mt-4 text-center">
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-shield-alt text-green-600 mr-1"></i>
                                Your payment is secured with SSL encryption
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Order Summary Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h2>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Booth Price</span>
                                <span class="font-medium">{{ formatRupiah($booking->total_price) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Service Fee</span>
                                <span class="font-medium text-green-600">FREE</span>
                            </div>
                            <div class="border-t pt-4">
                                <div class="flex justify-between text-lg font-bold">
                                    <span>Total Amount</span>
                                    <span class="text-[#ff7700]">{{ formatRupiah($booking->total_price) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Trust Badges -->
                        <div class="mt-6 pt-6 border-t">
                            <p class="text-sm text-gray-600 mb-3">We accept:</p>
                            <div class="grid grid-cols-3 gap-2">
                                <div class="bg-gray-50 rounded p-2 text-center">
                                    <i class="fas fa-credit-card text-gray-600 text-xl"></i>
                                    <p class="text-xs text-gray-600 mt-1">Cards</p>
                                </div>
                                <div class="bg-gray-50 rounded p-2 text-center">
                                    <i class="fas fa-university text-gray-600 text-xl"></i>
                                    <p class="text-xs text-gray-600 mt-1">Bank</p>
                                </div>
                                <div class="bg-gray-50 rounded p-2 text-center">
                                    <i class="fas fa-wallet text-gray-600 text-xl"></i>
                                    <p class="text-xs text-gray-600 mt-1">E-Wallet</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('components.footer')

    <script>
        document.getElementById('pay-button').addEventListener('click', function() {
            // Disable button and show loading
            const button = this;
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Initializing Payment...';

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            // Call initiate payment endpoint
            fetch('{{ route("payment.initiate", $booking->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.snap_token) {
                        // Initialize Snap
                        snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                window.location.href = '{{ route("payment.success", $booking->id) }}';
                            },
                            onPending: function(result) {
                                window.location.href = '{{ route("payment.pending", $booking->id) }}';
                            },
                            onError: function(result) {
                                window.location.href = '{{ route("payment.error", $booking->id) }}';
                            },
                            onClose: function() {
                                // Re-enable button
                                button.disabled = false;
                                button.innerHTML = '<i class="fas fa-lock mr-2"></i>Proceed to Secure Payment';
                            }
                        });
                    } else {
                        alert('Failed to initialize payment. Please try again.');
                        button.disabled = false;
                        button.innerHTML = '<i class="fas fa-lock mr-2"></i>Proceed to Secure Payment';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-lock mr-2"></i>Proceed to Secure Payment';
                });
        });
    </script>
</body>

</html>