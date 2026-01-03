<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book {{ $booth->name }} - {{ $event->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @viteCss
    @viteJs
</head>

@php
// Calculate event duration
$eventDates = '';
$eventDuration = 0;
if ($event->start_time && $event->end_time) {
$start = $event->start_time;
$end = $event->end_time;
$eventDuration = floor($start->diffInDays($end)) + 1;
if ($eventDuration == 1) {
$eventDates = $start->format('F d, Y') . ' (1 day)';
} else {
$eventDates = $start->format('F d') . ' - ' . $end->format('d, Y') . ' (' . $eventDuration . ' days)';
}
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
                    <div class="flex items-start justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-slate-800 mb-2">{{ $booth->name }}</h1>
                            <div class="flex flex-wrap gap-3 text-sm">
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-orange-100 text-[#ff7700] font-medium">
                                    <i class="fas fa-tag mr-2"></i>
                                    {{ ucfirst($booth->type ?? 'Standard') }} Booth
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-gray-800 font-medium">
                                    <i class="fas fa-ruler-combined mr-2"></i>
                                    {{ $booth->size ? $booth->size . ' cm' : 'Size not specified' }}
                                </span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-slate-600 mb-1">Price</div>
                            <div class="text-3xl font-bold text-[#ff7700]">{{ formatRupiah($booth->price) }}</div>
                            @if($eventDuration > 0)
                            <div class="text-xs text-slate-500 mt-1">for {{ $eventDuration }} {{ Str::plural('day', $eventDuration) }}</div>
                            @endif
                        </div>
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
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center text-[#ff7700] mr-4 flex-shrink-0">
                                <i class="fas fa-calendar-alt text-xl"></i>
                            </div>
                            <div>
                                <div class="text-xs text-slate-600 mb-1">Event Name</div>
                                <div class="font-semibold text-slate-900">{{ $event->title }}</div>
                            </div>
                        </div>

                        @if($eventDates)
                        <div class="flex items-start">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center text-[#ff7700] mr-4 flex-shrink-0">
                                <i class="fas fa-clock text-xl"></i>
                            </div>
                            <div>
                                <div class="text-xs text-slate-600 mb-1">Event Duration</div>
                                <div class="font-semibold text-slate-900">{{ $eventDates }}</div>
                            </div>
                        </div>
                        @endif

                        @if($event->venue || $event->display_location)
                        <div class="flex items-start">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center text-[#ff7700] mr-4 flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-xl"></i>
                            </div>
                            <div>
                                <div class="text-xs text-slate-600 mb-1">Location</div>
                                <div class="font-semibold text-slate-900">{{ $event->display_location ?? $event->venue }}</div>
                                @if($event->address)
                                <div class="text-xs text-slate-600 mt-1">{{ $event->address }}</div>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if($event->category)
                        <div class="flex items-start">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center text-[#ff7700] mr-4 flex-shrink-0">
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
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center text-[#ff7700] mr-4 flex-shrink-0">
                                <i class="fas fa-info-circle text-xl"></i>
                            </div>
                            <div>
                                <div class="text-xs text-slate-600 mb-1">About Event</div>
                                <div class="text-sm text-slate-700 leading-relaxed">{{ $event->description }}</div>
                            </div>
                        </div>
                        @endif

                        <div class="flex items-start">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center text-[#ff7700] mr-4 flex-shrink-0">
                                <i class="fas fa-{{ $event->refundable ? 'check-circle' : 'times-circle' }} text-xl"></i>
                            </div>
                            <div>
                                <div class="text-xs text-slate-600 mb-1">Refund Policy</div>
                                <div class="font-semibold {{ $event->refundable ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $event->refundable ? 'Refundable' : 'Non-Refundable' }}
                                </div>
                                <div class="text-xs text-slate-600 mt-1">
                                    {{ $event->refundable ? 'This booking can be refunded if cancelled' : 'This booking cannot be refunded once confirmed' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Form -->
                <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                    <h2 class="text-xl font-bold text-slate-800 mb-4 flex items-center">
                        <i class="fas fa-user-circle mr-2 text-[#ff7700]"></i>
                        Your Information
                    </h2>

                    <form id="bookingForm" method="POST" action="{{ route('bookings.store') }}" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        <input type="hidden" name="booth_id" value="{{ $booth->id }}">

                        <!-- Contact Information -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="full_name" required
                                value="{{ old('full_name', $user ? explode(' ', $user->display_name)[0] : '') }}"
                                class="w-full px-4 py-3 border {{ $errors->has('full_name') ? 'border-red-500' : 'border-slate-300' }} rounded-lg text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none transition-all"
                                placeholder="Enter your full name">
                            @error('full_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
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

                        <!-- Product Picture Upload -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Product Pictures <span class="text-red-500">*</span>
                            </label>
                            <p class="text-xs text-slate-600 mb-2">Upload a PDF containing pictures of your products (max 5MB)</p>
                            <div class="relative">
                                <input type="file" name="product_picture" id="productPictureUpload" class="hidden" accept=".pdf" required>
                                <button type="button" id="productUploadButton"
                                    class="w-full px-4 py-3 border-2 border-dashed {{ $errors->has('product_picture') ? 'border-red-500' : 'border-slate-300' }} rounded-lg text-sm text-slate-600 hover:border-[#ff7700] hover:text-[#ff7700] transition-colors duration-200 flex items-center justify-center gap-2">
                                    <i class="fas fa-cloud-upload-alt text-lg"></i>
                                    <span>Click to upload product pictures (PDF only)</span>
                                </button>

                                <!-- File Preview (Initially Hidden) -->
                                <div id="productFilePreview" class="hidden w-full px-4 py-3 border-2 border-green-500 rounded-lg bg-green-50 flex items-center justify-between">
                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                        <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-file-pdf text-red-600 text-lg"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p id="productFileName" class="text-sm font-medium text-gray-900 truncate"></p>
                                            <p id="productFileSize" class="text-xs text-gray-500"></p>
                                        </div>
                                    </div>
                                    <button type="button" id="removeProductFile" class="flex-shrink-0 ml-3 text-red-600 hover:text-red-800 transition-colors">
                                        <i class="fas fa-times-circle text-xl"></i>
                                    </button>
                                </div>
                            </div>
                            @error('product_picture')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p id="productPictureError" class="mt-1 text-sm text-red-600 hidden">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                Please upload product pictures before submitting your booking request.
                            </p>
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
                                    class="mt-1 mr-3 w-5 h-5 accent-[#ff7700] focus:ring-[#ff7700] border-slate-300 rounded">
                                <span class="text-sm text-slate-700">
                                    I agree to the
                                    @if($event->terms_and_conditions)
                                    <a href="{{ $event->terms_and_conditions }}" target="_blank" rel="noopener noreferrer" class="text-[#ff7700] hover:underline font-medium">
                                        Terms and Conditions <i class="fas fa-external-link-alt text-xs ml-1"></i>
                                    </a>
                                    @else
                                    <span class="text-[#ff7700] font-medium">Terms and Conditions</span>
                                    @endif
                                    of the event.
                                </span>
                            </label>
                        </div>

                        <!-- Submit Button (Desktop) -->
                        <div>
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
                                        <span class="text-slate-700">Booth Name</span>
                                        <span class="font-semibold text-slate-900">{{ $booth->name }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-700">Type</span>
                                        <span class="font-semibold text-slate-900">{{ ucfirst($booth->type ?? 'Standard') }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-700">Size</span>
                                        <span class="font-semibold text-slate-900">{{ $booth->size ? $booth->size . ' cm' : 'N/A' }}</span>
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
                                        <div class="font-semibold text-slate-900">{{ $eventDuration }} {{ Str::plural('day', $eventDuration) }}</div>
                                    </div>
                                    @endif
                                    @if($event->venue || $event->display_location)
                                    <div class="text-sm">
                                        <div class="text-slate-700 mb-1">Location</div>
                                        <div class="font-semibold text-slate-900">{{ $event->display_location ?? $event->venue }}</div>
                                        @if($event->address)
                                        <div class="text-xs text-slate-600 mt-1">{{ $event->address }}</div>
                                        @endif
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

        // Product Picture Upload Functionality
        const productFileInput = document.getElementById('productPictureUpload');
        const productUploadButton = document.getElementById('productUploadButton');
        const productFilePreview = document.getElementById('productFilePreview');
        const productFileName = document.getElementById('productFileName');
        const productFileSize = document.getElementById('productFileSize');
        const removeProductFileBtn = document.getElementById('removeProductFile');
        const productPictureError = document.getElementById('productPictureError');

        // Handle upload button click
        if (productUploadButton) {
            productUploadButton.addEventListener('click', function() {
                productFileInput.click();
            });
        }

        // Format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        // Handle file selection
        if (productFileInput) {
            productFileInput.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    this.setCustomValidity('');
                    const file = e.target.files[0];

                    // Update file info
                    productFileName.textContent = file.name;
                    productFileSize.textContent = formatFileSize(file.size);

                    // Hide upload button and show preview
                    productUploadButton.classList.add('hidden');
                    productFilePreview.classList.remove('hidden');

                    // Remove error styling and message
                    productUploadButton.classList.remove('border-red-500');
                    productUploadButton.classList.add('border-slate-300');
                    if (productPictureError) {
                        productPictureError.classList.add('hidden');
                    }
                }
            });

            // Handle invalid file input
            productFileInput.addEventListener('invalid', function(e) {
                e.preventDefault();
                this.setCustomValidity('Please upload product pictures.');

                // Add red border to upload button
                productUploadButton.classList.add('border-red-500');
                productUploadButton.classList.remove('border-slate-300');

                // Show error message
                if (productPictureError) {
                    productPictureError.classList.remove('hidden');
                }

                // Scroll to the upload section
                productUploadButton.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            });
        }

        // Handle file removal
        if (removeProductFileBtn) {
            removeProductFileBtn.addEventListener('click', function() {
                // Clear file input
                productFileInput.value = '';

                // Show upload button and hide preview
                productUploadButton.classList.remove('hidden');
                productFilePreview.classList.add('hidden');

                // Clear file info
                productFileName.textContent = '';
                productFileSize.textContent = '';

                // Hide error message
                if (productPictureError) {
                    productPictureError.classList.add('hidden');
                }
            });
        }

        // Simple form handling - let Laravel handle validation
        if (bookingForm) {
            bookingForm.addEventListener('submit', function(e) {
                // Check if product picture is uploaded
                if (!productFileInput.files || productFileInput.files.length === 0) {
                    e.preventDefault();
                    
                    // Show error message
                    if (productPictureError) {
                        productPictureError.classList.remove('hidden');
                    }
                    
                    // Add red border to upload button
                    productUploadButton.classList.add('border-red-500');
                    productUploadButton.classList.remove('border-slate-300');
                    
                    // Scroll to the upload section
                    productUploadButton.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    
                    return false;
                }

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