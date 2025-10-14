<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book {{ $booth->number }} - {{ $event->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@php
// Helper function to format rupiah
if (!function_exists('formatRupiah')) {
function formatRupiah($value) {
$digits = preg_replace('/\D/', '', (string) $value);
$num = $digits === '' ? 0 : intval($digits);
return 'Rp' . number_format($num, 0, ',', '.');
}
}

// Calculate event duration
$eventDates = '';
$eventDuration = 0;
if ($event->start_time && $event->end_time) {
$start = $event->start_time;
$end = $event->end_time;
$eventDuration = floor($start->diffInDays($end)) + 1;
$eventDates = $start->format('F d') . ' - ' . $end->format('d, Y') . ' (' . $eventDuration . ' days)';
}

$totalAmount = $booth->price;

// Get authenticated user data for autofill
$user = auth()->user();
// Strip +62 or 62 prefix from phone number
$userPhone = '';
if ($user && $user->phone_number) {
$digits = preg_replace('/\D+/', '', $user->phone_number);
if (strpos($digits, '62') === 0) {
$userPhone = substr($digits, 2);
} elseif (strpos($digits, '0') === 0) {
$userPhone = substr($digits, 1);
} else {
$userPhone = $digits;
}
}
@endphp

<body class="bg-white min-h-screen">
    @include('components.navbar')

    <div class="container mx-auto px-4 py-8 max-w-7xl">
        @include('components.back-button', [
        'text' => 'Back to Booth Selection',
        'url' => route('booths.index', ['event' => $event->id])
        ])

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg relative" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3 text-xl"></i>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg relative" role="alert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-3 text-xl"></i>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg relative" role="alert">
            <div class="flex items-start">
                <i class="fas fa-exclamation-triangle mr-3 text-xl mt-1"></i>
                <div>
                    <p class="font-semibold mb-2">Please correct the following errors:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content - Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Booth Information Card -->
                <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-slate-800 mb-2">{{ $booth->number }}</h1>
                            <div class="flex flex-wrap gap-3 text-sm">
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800 font-medium">
                                    <i class="fas fa-tag mr-2"></i>
                                    {{ ucfirst($booth->type ?? 'Standard') }} Booth
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-purple-100 text-purple-800 font-medium">
                                    <i class="fas fa-ruler-combined mr-2"></i>
                                    {{ $booth->size ?? 'Size not specified' }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-800 font-medium">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Available
                                </span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-slate-600 mb-1">Price</div>
                            <div class="text-3xl font-bold text-[#ff7700]">{{ formatRupiah($booth->price) }}</div>
                            @if($eventDuration > 0)
                            <div class="text-xs text-slate-500 mt-1">for {{ $eventDuration }} days</div>
                            @endif
                        </div>
                    </div>

                    <div class="prose max-w-none">
                        <p class="text-slate-700 leading-relaxed">
                            Secure your spot at {{ $event->title }} with this premium booth location. This booth offers excellent visibility and foot traffic, perfect for showcasing your products and engaging with potential customers throughout the event.
                        </p>
                    </div>
                </div>

                <!-- Event Information Card -->
                <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                    <h2 class="text-xl font-bold text-slate-800 mb-4 flex items-center">
                        <i class="fas fa-calendar-star mr-2 text-[#ff7700]"></i>
                        Event Information
                    </h2>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-12 h-12 rounded-lg bg-orange-100 flex items-center justify-center text-[#ff7700] mr-4 flex-shrink-0">
                                <i class="fas fa-calendar-alt text-xl"></i>
                            </div>
                            <div>
                                <div class="text-xs text-slate-600 mb-1">Event Name</div>
                                <div class="font-semibold text-slate-900">{{ $event->title }}</div>
                            </div>
                        </div>

                        @if($eventDates)
                        <div class="flex items-start">
                            <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 mr-4 flex-shrink-0">
                                <i class="fas fa-clock text-xl"></i>
                            </div>
                            <div>
                                <div class="text-xs text-slate-600 mb-1">Event Duration</div>
                                <div class="font-semibold text-slate-900">{{ $eventDates }}</div>
                            </div>
                        </div>
                        @endif

                        @if($event->venue)
                        <div class="flex items-start">
                            <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center text-green-600 mr-4 flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-xl"></i>
                            </div>
                            <div>
                                <div class="text-xs text-slate-600 mb-1">Venue</div>
                                <div class="font-semibold text-slate-900">{{ $event->venue }}</div>
                            </div>
                        </div>
                        @endif

                        @if($event->category)
                        <div class="flex items-start">
                            <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600 mr-4 flex-shrink-0">
                                <i class="fas fa-layer-group text-xl"></i>
                            </div>
                            <div>
                                <div class="text-xs text-slate-600 mb-1">Category</div>
                                <div class="font-semibold text-slate-900">{{ $event->category->name ?? 'N/A' }}</div>
                            </div>
                        </div>
                        @endif

                        @if($event->description)
                        <div class="flex items-start">
                            <div class="w-12 h-12 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600 mr-4 flex-shrink-0">
                                <i class="fas fa-info-circle text-xl"></i>
                            </div>
                            <div>
                                <div class="text-xs text-slate-600 mb-1">About Event</div>
                                <div class="text-sm text-slate-700 leading-relaxed">{{ $event->description }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Booking Form -->
                <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                    <h2 class="text-xl font-bold text-slate-800 mb-4 flex items-center">
                        <i class="fas fa-user-circle mr-2 text-[#ff7700]"></i>
                        Your Information
                    </h2>

                    <form id="bookingForm" method="POST" action="{{ route('bookings.store') }}" class="space-y-5">
                        @csrf
                        <input type="hidden" name="booth_id" value="{{ $booth->id }}">

                        <!-- Contact Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    First Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="first_name" required
                                    value="{{ old('first_name', $user ? explode(' ', $user->display_name)[0] : '') }}"
                                    class="w-full px-4 py-3 border {{ $errors->has('first_name') ? 'border-red-500' : 'border-slate-300' }} rounded-lg text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none transition-all"
                                    placeholder="Enter your first name">
                                @error('first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    Last Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="last_name" required
                                    value="{{ old('last_name', $user && str_word_count($user->display_name) > 1 ? substr($user->display_name, strpos($user->display_name, ' ') + 1) : '') }}"
                                    class="w-full px-4 py-3 border {{ $errors->has('last_name') ? 'border-red-500' : 'border-slate-300' }} rounded-lg text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none transition-all"
                                    placeholder="Enter your last name">
                                @error('last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Business/Company Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="business_name" required
                                value="{{ old('business_name', $user ? $user->name : '') }}"
                                class="w-full px-4 py-3 border {{ $errors->has('business_name') ? 'border-red-500' : 'border-slate-300' }} rounded-lg text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none transition-all"
                                placeholder="Enter your business or company name">
                            @error('business_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" required
                                    value="{{ old('email', $user ? $user->email : '') }}"
                                    class="w-full px-4 py-3 border {{ $errors->has('email') ? 'border-red-500' : 'border-slate-300' }} rounded-lg text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none transition-all"
                                    placeholder="your@email.com">
                                @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    Phone Number <span class="text-red-500">*</span>
                                </label>
                                <div class="flex border {{ $errors->has('phone') ? 'border-red-500' : 'border-slate-300' }} rounded-lg transition-all focus-within:ring-2 focus-within:ring-[#ff7700] focus-within:border-[#ff7700]">
                                    <div class="bg-slate-50 border-0 rounded-l-lg px-4 py-3 text-slate-700 text-sm flex items-center border-r border-slate-300">
                                        +62
                                    </div>
                                    <input type="tel" name="phone" required
                                        value="{{ old('phone', $userPhone) }}"
                                        class="flex-1 px-4 py-3 border-0 rounded-r-lg text-sm focus:outline-none focus:ring-0"
                                        placeholder="878-8722-2123">
                                </div>
                                @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Additional Notes <span class="text-slate-400 font-normal">(Optional)</span>
                            </label>
                            <textarea name="notes" rows="4"
                                class="w-full px-4 py-3 border {{ $errors->has('notes') ? 'border-red-500' : 'border-slate-300' }} rounded-lg text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none transition-all resize-none"
                                placeholder="Any special requirements or requests for your booth setup...">{{ old('notes') }}</textarea>
                            @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
                            <label class="flex items-start cursor-pointer">
                                <input type="checkbox" id="agreeTerms" required
                                    class="mt-1 mr-3 w-5 h-5 text-[#ff7700] border-slate-300 rounded focus:ring-[#ff7700]">
                                <span class="text-sm text-slate-700">
                                    I agree to the <a href="#" class="text-[#ff7700] hover:underline font-medium">Terms and Conditions</a>
                                    and <a href="#" class="text-[#ff7700] hover:underline font-medium">Cancellation Policy</a>.
                                    I understand that booth assignments are final once confirmed.
                                </span>
                            </label>
                        </div>

                        <!-- Submit Button (Desktop) -->
                        <div class="hidden lg:block">
                            <button type="submit"
                                class="w-full bg-[#ff7700] hover:bg-[#e66600] text-white font-bold text-lg py-4 px-6 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-3">
                                Request Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar - Right Column -->
            <div class="lg:col-span-1">
                <!-- Booking Summary (Sticky) -->
                <div class="sticky top-8 space-y-6">
                    <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center">
                            <i class="fas fa-receipt mr-2 text-[#ff7700]"></i>
                            Booking Summary
                        </h3>

                        <div class="space-y-4">
                            <!-- Booth Details -->
                            <div class="pb-4 border-b border-slate-200">
                                <div class="text-xs text-slate-600 mb-2">BOOTH DETAILS</div>
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-700">Booth Number</span>
                                        <span class="font-semibold text-slate-900">{{ $booth->number }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-700">Type</span>
                                        <span class="font-semibold text-slate-900">{{ ucfirst($booth->type ?? 'Standard') }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-700">Size</span>
                                        <span class="font-semibold text-slate-900">{{ $booth->size ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Event Details -->
                            <div class="pb-4 border-b border-slate-200">
                                <div class="text-xs text-slate-600 mb-2">EVENT DETAILS</div>
                                <div class="space-y-2">
                                    <div class="text-sm">
                                        <div class="text-slate-700 mb-1">Event Name</div>
                                        <div class="font-semibold text-slate-900">{{ $event->title }}</div>
                                    </div>
                                    @if($eventDates)
                                    <div class="text-sm">
                                        <div class="text-slate-700 mb-1">Duration</div>
                                        <div class="font-semibold text-slate-900">{{ $eventDuration }} days</div>
                                    </div>
                                    @endif
                                    @if($event->venue)
                                    <div class="text-sm">
                                        <div class="text-slate-700 mb-1">Venue</div>
                                        <div class="font-semibold text-slate-900">{{ $event->venue }}</div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Price Breakdown -->
                            <div class="pb-4 border-b border-slate-200">
                                <div class="text-xs text-slate-600 mb-3">PRICE BREAKDOWN</div>
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-700">Booth Rental</span>
                                        <span class="font-semibold text-slate-900">{{ formatRupiah($booth->price) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-700">Service Fee</span>
                                        <span class="font-semibold text-slate-900">Rp0</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-700">Tax (0%)</span>
                                        <span class="font-semibold text-slate-900">Rp0</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Total -->
                            <div class="pt-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-slate-900">Total Amount</span>
                                    <span class="text-2xl font-bold text-[#ff7700]">{{ formatRupiah($totalAmount) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Submit Button -->
                    <div class="lg:hidden">
                        <button type="submit" form="bookingForm"
                            class="w-full bg-[#ff7700] hover:bg-[#e66600] text-white font-bold text-lg py-4 px-6 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-3">
                            <i class="fas fa-credit-card"></i>
                            Proceed to Payment
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.footer')

    <script>
        const formatLocalPhone = (value) => {
            const digits = String(value || '').replace(/\D+/g, '');
            if (!digits) {
                return '';
            }
            if (digits.length <= 3) {
                return digits;
            }

            const firstBlock = digits.slice(0, 3);
            const remainder = digits.slice(3);
            const chunks = remainder.match(/.{1,4}/g) || [];

            return [firstBlock, ...chunks].join('-');
        };

        const bookingForm = document.getElementById('bookingForm');
        const phoneInput = document.querySelector('input[name="phone"]');

        if (phoneInput) {
            const applyFormattedPhone = () => {
                phoneInput.value = formatLocalPhone(phoneInput.value);
            };

            applyFormattedPhone();

            phoneInput.addEventListener('input', () => {
                const caretPosition = phoneInput.selectionStart || 0;
                const previousLength = phoneInput.value.length;
                applyFormattedPhone();
                const newLength = phoneInput.value.length;
                const diff = newLength - previousLength;
                const newPosition = Math.min(Math.max(0, caretPosition + diff), newLength);
                phoneInput.setSelectionRange(newPosition, newPosition);
            });

            phoneInput.addEventListener('blur', applyFormattedPhone);
        }

        // Simple form handling - let Laravel handle validation
        if (bookingForm) {
            bookingForm.addEventListener('submit', function() {
                if (phoneInput) {
                    phoneInput.value = phoneInput.value.replace(/\D+/g, '');
                }

                const submitButton = this.querySelector('button[type="submit"]');

                // Disable submit button to prevent double submission
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
                }
            });
        }

        // Scroll to error messages if they exist
        document.addEventListener('DOMContentLoaded', function() {
            if (phoneInput) {
                phoneInput.value = formatLocalPhone(phoneInput.value);
            }

            const errorAlert = document.querySelector('[role="alert"]');
            if (errorAlert) {
                errorAlert.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        });
    </script>
</body>

</html>
