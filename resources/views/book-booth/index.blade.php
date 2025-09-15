<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Book Booth A02 - Tech Innovation Expo 2025</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

@php
// Mock booth data
if (!function_exists('formatRupiah')) {
function formatRupiah($value) {
$digits = preg_replace('/\D/', '', (string) $value);
$num = $digits === '' ? 0 : intval($digits);
return 'Rp' . number_format($num, 0, ',', '.');
}
}

$booth = [
'number' => 'A02',
'title' => 'Booth A02',
'price' => 500000,
'size' => '3m x 3m (9 sqm)',
'description' => 'Prime location booth in the main exhibition hall with excellent visibility and foot traffic. Perfect for showcasing your products and engaging with potential customers. This front-section booth offers maximum exposure during the event.',
'dates' => 'November 16-20, 2025 (5 days)'
];

$totalAmount = $booth['price'];
$deposit = 250000; // mocked deposit or partial amount
@endphp

<body class="bg-gray-50 min-h-screen">
    <!-- Navbar -->
    @include('components.navbar')

    <!-- Main Content -->
    <div class="min-h-screen py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            @include('components.back-button', ['text' => 'Back to Event Details', 'url' => '/events/details'])

            <!-- Booth Header -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">

                <div class="p-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-3xl font-bold text-gray-900">{{ $booth['title'] }}</h2>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-[#ff7700]">{{ formatRupiah($booth['price']) }}</div>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-ruler-combined mr-2 text-[#ff7700]"></i>
                            <span>{{ $booth['size'] }}</span>
                        </div>
                    </div>
                    <p class="text-gray-700 leading-relaxed">{{ $booth['description'] }}</p>
                </div>
            </div>

            <!-- Booking Form -->
            <div class="bg-white rounded-lg shadow-md p-8">
                <form id="bookingForm" class="space-y-6">
                    <!-- Booth Summary -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-medium text-gray-900 text-lg">{{ $booth['title'] }}</span>
                            <span class="font-bold text-[#ff7700] text-xl">{{ formatRupiah($booth['price']) }}</span>
                        </div>
                        <div class="text-gray-600">

                            <p class="flex items-center mb-1">
                                <i class="fas fa-ruler-combined mr-2 text-[#ff7700]"></i>
                                {{ $booth['size'] }}
                            </p>
                            <p class="flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-[#ff7700]"></i>
                                {{ $booth['dates'] }}
                            </p>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="space-y-4">
                        <h3 class="text-xl font-semibold text-gray-900">Contact Information</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                                <input type="text" required class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                                <input type="text" required class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Your Business Name *</label>
                            <input type="text" required class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                <input type="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                                <input type="tel" required class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- Special Requests -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                        <textarea rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none" placeholder="Any special requirements or requests..."></textarea>
                    </div>

                    <!-- Total -->
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-lg font-semibold text-gray-900">Total Amount</span>
                        <span class="text-2xl font-bold text-[#ff7700]">{{ formatRupiah($totalAmount) }}</span>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="hover:cursor-pointer w-full bg-[#ff7700] hover:bg-[#e66600] text-white font-semibold text-lg py-2.5 px-2 rounded-lg transition-colors duration-200">
                        Proceed to Payment
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('components.footer')

    <script>
        // Handle form submission
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Basic validation
            const requiredFields = document.querySelectorAll('input[required], #agreeTerms');
            let isValid = true;

            requiredFields.forEach(field => {
                if (field.type === 'checkbox') {
                    if (!field.checked) {
                        field.classList.add('border-red-500');
                        isValid = false;
                    } else {
                        field.classList.remove('border-red-500');
                    }
                } else {
                    if (!field.value.trim()) {
                        field.classList.add('border-red-500');
                        isValid = false;
                    } else {
                        field.classList.remove('border-red-500');
                    }
                }
            });

            if (isValid) {
                // Show success message (in real app, this would redirect to payment)
                alert('Booking form submitted successfully! You will be redirected to payment processing.');
            } else {
                alert('Please fill in all required fields and agree to the terms.');
            }
        });
    </script>
</body>

</html>