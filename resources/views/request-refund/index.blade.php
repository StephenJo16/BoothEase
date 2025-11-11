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

            <!-- Success/Error Messages -->
            @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
            @endif

            @if (session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
            @endif

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
                                    placeholder="Enter account number">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Booking Invoice <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="file" name="document" id="fileUpload" class="hidden" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <button type="button" id="uploadButton"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-left text-gray-500 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-transparent">
                                        Choose File
                                    </button>

                                    <!-- File Preview -->
                                    <div id="filePreview" class="hidden mt-2 px-3 py-2 border border-gray-300 rounded-lg bg-gray-50">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-2 flex-1 min-w-0">
                                                <!-- File Icon -->
                                                <div class="flex-shrink-0">
                                                    <svg class="w-6 h-6 text-[#ff7700]" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <!-- File Info -->
                                                <div class="flex-1 min-w-0">
                                                    <p id="fileName" class="text-sm font-medium text-gray-900 truncate"></p>
                                                    <p id="fileSize" class="text-xs text-gray-500"></p>
                                                </div>
                                            </div>
                                            <!-- Delete Button -->
                                            <button type="button" id="removeFile" class="flex-shrink-0 ml-2 text-red-500 hover:text-red-700 transition-colors">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Refund Reason -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Refund Reason <span class="text-red-500">*</span></label>
                            <textarea rows="4" name="reason" required
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-transparent resize-none @error('reason') border-red-500 @enderror"
                                placeholder="Please explain why you need a refund...">{{ old('reason') }}</textarea>
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
                                    <span class="text-gray-600">Booth Number:</span>
                                    <span class="font-medium text-gray-900">{{ $booking->booth->number }}</span>
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

            <!-- Important Information -->
            <div class="mt-8 bg-red-50 border border-red-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-red-800 mb-2">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Important Information
                </h3>
                <ul class="text-sm text-red-700 space-y-1">
                    <li>• Refund processing may take 5-10 business days after approval</li>
                    <li>• Processing fees are non-refundable and will be deducted from your refund amount</li>
                    <li>• Cancellations made less than 7 days before the event may incur additional penalties</li>
                    <li>• All refund requests are subject to event organizer approval</li>
                    <li>• You will receive email updates regarding the status of your refund request</li>
                </ul>
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
                const file = e.target.files[0];

                // Update file info
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);

                // Hide upload button and show preview
                uploadButton.classList.add('hidden');
                filePreview.classList.remove('hidden');
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

        // Form submission validation
        document.querySelector('form').addEventListener('submit', function(e) {
            // Check if file is uploaded
            if (fileInput.files.length === 0) {
                e.preventDefault();

                // Show error message
                alert('Please upload the booking invoice before submitting the refund request.');

                // Add red border to upload button
                uploadButton.classList.add('border-red-500');
                uploadButton.classList.remove('border-gray-300');

                // Scroll to the upload section
                uploadButton.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });

                return false;
            }
        });
    </script>
</body>

</html>