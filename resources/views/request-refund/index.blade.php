<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Refund Request - BoothEase</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

@php
// Format event dates using helper function
$dateDisplay = formatEventDate($event);
@endphp

<body class="bg-gray-50 min-h-screen font-['Instrument_Sans']">
    <!-- Navbar -->
    @include('components.navbar')

    <!-- Main Content -->
    <div class="min-h-screen py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            @include('components.back-button', ['url' => route('my-booking-details', $booking->id), 'text' => 'Back to Booking Details'])

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Request Refund</h1>
                <p class="text-gray-600">Booking ID: ID-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>

            @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg" role="alert">
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="font-semibold mb-1">Please fix the following errors:</p>
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Refund Request Form -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Refund Request Details</h2>
                    <p class="text-sm text-gray-600 mb-6">Please provide a reason for your refund request and bank details
                    </p>

                    <form method="POST" action="{{ route('refund-request.store', $booking->id) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Account Holder Name -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Account Holder Name <span class="text-red-500">*</span></label>
                                <input type="text" name="account_holder_name" value="{{ old('account_holder_name') }}" required
                                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-transparent @error('account_holder_name') border-red-500 @enderror"
                                    placeholder="Enter account holder name">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bank Name <span class="text-red-500">*</span></label>
                                <input type="text" name="bank_name" value="{{ old('bank_name') }}" required
                                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-transparent @error('bank_name') border-red-500 @enderror"
                                    placeholder="Enter bank name">
                            </div>
                        </div>

                        <!-- Account Number -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Account Number <span class="text-red-500">*</span></label>
                                <input type="text" name="account_number" value="{{ old('account_number') }}" required
                                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-transparent @error('account_number') border-red-500 @enderror"
                                    placeholder="Enter account number" inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Booking Invoice <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="file" name="document" id="fileUpload" class="hidden" accept=".pdf" required>
                                    <button type="button" id="uploadButton"
                                        class="w-full px-3 py-1 border-2 border-dashed @error('document') border-red-500 @else border-gray-300 @enderror rounded-lg text-sm text-gray-600 hover:border-[#ff7700] hover:text-[#ff7700] transition-colors duration-200 flex items-center justify-center gap-2">
                                        <i class="fas fa-cloud-upload-alt text-lg"></i>
                                        <span>Click to upload invoice</span>
                                    </button>

                                    <!-- File Preview -->
                                    <div id="filePreview" class="hidden w-full px-3 border-2 border-orange-500 rounded-lg bg-orange-50 flex items-center justify-between">
                                        <div class="flex items-center gap-3 flex-1 min-w-0">
                                            <div class="flex-shrink-0">
                                                <svg class="w-6 h-6 text-[#ff7700]" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p id="fileName" class="text-sm font-medium text-gray-900 truncate"></p>
                                                <p id="fileSize" class="text-xs text-gray-500"></p>
                                            </div>
                                        </div>
                                        <button type="button" id="removeFile" class="flex-shrink-0 ml-3 text-red-600 hover:text-red-800 transition-colors">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                    @error('document')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Refund Reason -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Refund Reason <span class="text-red-500">*</span></label>
                            <textarea rows="4" name="reason" required
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-transparent resize-none @error('reason') border-red-500 @enderror"
                                placeholder="Please explain why you need a refund... (at least 10 characters)">{{ old('reason') }}</textarea>
                            @error('reason')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Refund Policy -->
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-orange-800 mb-2">Refund Policy</h4>
                            <p class="text-xs text-orange-700 leading-relaxed">
                                Refunds are subject to event organizer approval. Processing may take 5-10 business days.
                                Cancellations within 30 days of the event may incur additional fees.
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="w-full bg-[#ff7700] hover:bg-[#e66600] text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                            Submit Refund Request
                        </button>
                    </form>
                </div>

                <!-- Booking Summary -->
                <!-- Booking Summary -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Booking Summary</h2>

                    <div class="space-y-4">
                        <!-- Event Details -->
                        <div class="border-b border-gray-200 pb-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Event Details</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Event:</span>
                                    <span class="font-medium text-gray-900">{{ $event->title }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Venue:</span>
                                    <span class="font-medium text-gray-900">{{ $event->venue ?? 'Venue not specified' }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Date:</span>
                                    <span class="font-medium text-gray-900">{{ $dateDisplay }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Booth Details -->
                        <div class="border-b border-gray-200 pb-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Booth Details</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Booth Name:</span>
                                    <span class="font-medium text-gray-900">{{ $booking->booth->name }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Type:</span>
                                    <span class="font-medium text-gray-900">{{ ucfirst($booking->booth->type) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Details -->
                        <div class="border-b border-gray-200 pb-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Payment Details</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Booking Amount:</span>
                                    <span class="font-medium text-gray-900">{{ formatRupiah($booking->total_price) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Processing Fee (30%):</span>
                                    <span class="font-medium text-red-600">-
                                        {{ formatRupiah($processingFee) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Refund Amount -->
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-semibold text-gray-900">Estimated Refund Amount:</span>
                                <span class="text-lg font-bold text-[#ff7700]">{{ formatRupiah($refundAmount) }}</span>
                            </div>
                            <p class="text-xs text-gray-600 mt-2">
                                Final refund amount will be confirmed after review by the event organizer.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('components.footer')

    <script>
        // File upload functionality
        const fileInput = document.getElementById('fileUpload');
        const uploadButton = document.getElementById('uploadButton');
        const filePreview = document.getElementById('filePreview');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const removeFileBtn = document.getElementById('removeFile');

        // Handle upload button click
        uploadButton.addEventListener('click', function() {
            fileInput.click();
        });

        // Format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        // Handle file selection
        fileInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                this.setCustomValidity('');
                const file = e.target.files[0];
                const maxSize = 5 * 1024 * 1024; // 5MB in bytes

                // Validate file type
                if (file.type !== 'application/pdf') {
                    alert('Please upload a PDF file only.');
                    fileInput.value = '';
                    return;
                }

                // Validate file size
                if (file.size > maxSize) {
                    alert('File size must not exceed 5MB. Your file is ' + formatFileSize(file.size));
                    fileInput.value = '';
                    return;
                }

                // Update file info
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);

                // Hide upload button and show preview
                uploadButton.classList.add('hidden');
                filePreview.classList.remove('hidden');

                // Remove error styling and message
                uploadButton.classList.remove('border-red-500');
                uploadButton.classList.add('border-gray-300');
                const errorMsg = uploadButton.parentElement.querySelector('.file-upload-error');
                if (errorMsg) {
                    errorMsg.remove();
                }
            }
        });

        // Handle file removal
        removeFileBtn.addEventListener('click', function() {
            // Clear file input
            fileInput.value = '';

            // Show upload button and hide preview
            uploadButton.classList.remove('hidden');
            filePreview.classList.add('hidden');

            // Clear file info
            fileName.textContent = '';
            fileSize.textContent = '';
        });

        // Handle invalid file input
        fileInput.addEventListener('invalid', function(e) {
            e.preventDefault();
            this.setCustomValidity('Please upload the booking invoice.');

            // Add red border to upload button
            uploadButton.classList.add('border-red-500');
            uploadButton.classList.remove('border-gray-300');

            // Create or update error message
            let errorMsg = uploadButton.parentElement.querySelector('.file-upload-error');
            if (!errorMsg) {
                errorMsg = document.createElement('p');
                errorMsg.className = 'mt-1 text-sm text-red-600 file-upload-error';
                errorMsg.textContent = 'Please upload the booking invoice in the booking details page before submitting the refund request.';
                uploadButton.parentElement.appendChild(errorMsg);
            }

            // Scroll to the upload section
            uploadButton.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        });

        // Prevent double submission
        document.querySelector('form').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');

            // If button is already disabled, prevent submission
            if (submitBtn.disabled) {
                e.preventDefault();
                return;
            }

            // Disable button and show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
        });
    </script>
</body>

</html>