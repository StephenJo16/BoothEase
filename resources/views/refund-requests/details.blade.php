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
    @viteCss
    @viteJs
</head>

<body class="bg-gray-50 min-h-screen font-['Instrument_Sans']">
    @include('components.navbar')

    <!-- Main Content -->
    <div class="min-h-screen py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('components.back-button', ['url' => route('refund-requests', ['event' => $event->id]), 'text' => 'Back to Refund Requests'])

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Refund Request Details</h1>
                <p class="text-gray-600">Request ID: REQ-{{ str_pad($refundRequest->id, 4, '0', STR_PAD_LEFT) }}</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Refund Request Details -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-start mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Request Information</h2>
                        <!-- Status -->
                        <div>
                            @if($refundRequest->status === 'pending')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i> Pending
                            </span>
                            @elseif($refundRequest->status === 'approved')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check mr-1"></i> Approved
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times mr-1"></i> {{ ucfirst($refundRequest->status) }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Tenant and Booking Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tenant Name</label>
                            <p class="text-sm text-gray-900 font-medium">{{ $refundRequest->user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Booking ID</label>
                            <p class="text-sm text-gray-900 font-medium">ID-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>

                    <!-- Bank Account Details Section -->
                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Bank Account Details</h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Account Holder Name</label>
                                    <p class="text-sm text-gray-900">{{ $refundRequest->account_holder_name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Bank Name</label>
                                    <p class="text-sm text-gray-900">{{ $refundRequest->bank_name }}</p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Account Number</label>
                                <p class="text-sm text-gray-900 font-mono">{{ $refundRequest->account_number }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Request Details Section -->
                    <div class="mb-6">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Submitted At</label>
                            <p class="text-sm text-gray-900">
                                <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
                                {{ $refundRequest->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Refund Reason</label>
                            <div class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 min-h-[100px] text-sm text-gray-700">
                                {{ $refundRequest->reason }}
                            </div>
                        </div>
                    </div>

                    @if($refundRequest->document)
                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Supporting Document</label>
                        <a href="{{ asset('storage/' . $refundRequest->document) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm text-[#ff7700] hover:bg-gray-100 hover:text-[#e66600] transition-colors">
                            <i class="fas fa-file-pdf mr-2"></i>
                            View Uploaded Document
                        </a>
                    </div>
                    @endif

                    <!-- Refund Calculation -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Refund Calculation</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Original Booking Amount:</span>
                                <span class="font-medium text-gray-900">{{ formatRupiah($booking->total_price) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Processing Fee (30%):</span>
                                <span class="font-medium text-red-600">- {{ formatRupiah($refundRequest->processing_fee) }}</span>
                            </div>
                            <div class="pt-2 mt-2 border-t border-gray-300">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-semibold text-gray-900">Refund Amount:</span>
                                    <span class="text-2xl font-bold text-[#ff7700]">{{ formatRupiah($refundRequest->refund_amount) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    @if($refundRequest->isPending())
                    <!-- Buttons View -->
                    <div id="actionButtons" class="grid grid-cols-2 gap-3">
                        <form method="POST" action="{{ route('refund-requests.approve', ['event' => $event->id, 'refundRequest' => $refundRequest->id]) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" onclick="return confirm('Are you sure you want to approve this refund request? The refund amount will be processed to the tenant\'s bank account.')" class="hover:cursor-pointer w-full bg-green-500 hover:bg-green-600 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                                <i class="fas fa-check mr-2"></i>
                                Approve
                            </button>
                        </form>
                        <button type="button" onclick="showRejectForm()" class="hover:cursor-pointer w-full bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Reject
                        </button>
                    </div>

                    <!-- Rejection Form (Hidden by default) -->
                    <div id="rejectForm" class="hidden">
                        <form method="POST" action="{{ route('refund-requests.reject', ['event' => $event->id, 'refundRequest' => $refundRequest->id]) }}">
                            @csrf
                            @method('PATCH')

                            <div class="mb-4">
                                <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                    Rejection Reason <span class="text-red-500">*</span>
                                </label>
                                <textarea
                                    name="rejection_reason"
                                    id="rejection_reason"
                                    rows="5"
                                    required
                                    minlength="10"
                                    maxlength="1000"
                                    placeholder="Please provide a clear reason for rejecting this refund request (minimum 10 characters)..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"></textarea>
                                <p class="text-xs text-gray-500 mt-1">
                                    <span id="charCount">0</span>/1000 characters (minimum 10 required)
                                </p>
                                @error('rejection_reason')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                                <p class="text-sm text-yellow-800">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    This action cannot be undone. The tenant will be notified of the rejection and your reason.
                                </p>
                            </div>

                            <div class="flex gap-3">
                                <button
                                    type="button"
                                    onclick="hideRejectForm()"
                                    class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    class="hover:cursor-pointer flex-1 bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-times mr-2"></i>
                                    Confirm Rejection
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif
                    @if($refundRequest->isRejected() && $refundRequest->rejection_reason)
                    <!-- Rejection Details -->
                    <div class="mt-6 p-4 bg-red-50 rounded-lg border border-red-200">
                        <h3 class="text-sm font-semibold text-red-900 mb-2">
                            <i class="fas fa-exclamation-circle mr-2"></i>Rejection Reason
                        </h3>
                        <p class="text-sm text-red-800 mb-2">{{ $refundRequest->rejection_reason }}</p>
                        @if($refundRequest->rejected_at)
                        <p class="text-xs text-red-600 mt-2">
                            <i class="fas fa-clock mr-1"></i>
                            Rejected on {{ $refundRequest->rejected_at->format('d M Y, H:i') }}
                        </p>
                        @endif
                    </div>
                    @endif
                </div>


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
                                    <span class="font-medium text-red-600">- {{ formatRupiah($refundRequest->processing_fee) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Refund Amount -->
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-semibold text-gray-900">Estimated Refund Amount:</span>
                                <span class="text-lg font-bold text-[#ff7700]">{{ formatRupiah($refundRequest->refund_amount) }}</span>
                            </div>
                            <p class="text-xs text-gray-600 mt-2">
                                Processing fee is non-refundable as per the refund policy.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showRejectForm() {
            document.getElementById('actionButtons').classList.add('hidden');
            document.getElementById('rejectForm').classList.remove('hidden');
            // Focus on textarea
            document.getElementById('rejection_reason').focus();
        }

        function hideRejectForm() {
            document.getElementById('rejectForm').classList.add('hidden');
            document.getElementById('actionButtons').classList.remove('hidden');
            // Clear textarea
            document.getElementById('rejection_reason').value = '';
            document.getElementById('charCount').textContent = '0';
        }

        // Character counter
        document.getElementById('rejection_reason')?.addEventListener('input', function() {
            document.getElementById('charCount').textContent = this.value.length;
        });
    </script>

    @include('components.footer')
</body>

</html>