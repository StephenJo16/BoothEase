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
    @viteCss
    @viteJs
</head>

@php
$bookingStatus=getBookingStatusDisplay($booking->status);

// Calculate tenant's average rating
$tenant = $booking->user;
$tenantRatings = $tenant->ratingsReceived;
$averageRating = $tenantRatings->count() > 0 ? round($tenantRatings->avg('rating'), 1) : 0;
$totalRatings = $tenantRatings->count();
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
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $bookingStatus['class'] }}">{{ $bookingStatus['label'] }}</span>
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
                                <div class="text-gray-900 font-medium">{{ $booking->booth->name ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Floor no.</label>
                                <div class="text-gray-900">{{ $booking->booth->floor_number ?? 'N/A' }}</div>
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
                        @if($booking->product_picture)
                        @php
                        $productPictures = json_decode($booking->product_picture, true) ?: [];
                        @endphp

                        <!-- Product Pictures Section -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Product Pictures ({{ count($productPictures) }})</label>
                            <div class="space-y-3">
                                @foreach($productPictures as $index => $picture)
                                @php
                                $fileName = basename($picture);
                                $filePath = storage_path('app/public/' . $picture);
                                $fileSize = file_exists($filePath) ? filesize($filePath) : 0;
                                $fileSizeFormatted = $fileSize > 0 ? number_format($fileSize / 1024, 2) . ' KB' : 'Unknown';
                                @endphp
                                <div class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg bg-gray-50 hover:border-[#ff7700] transition-colors">
                                    <div class="flex items-center gap-3">
                                        <!-- Image Thumbnail -->
                                        <div class="flex-shrink-0">
                                            <div class="w-12 h-12 rounded-lg overflow-hidden border border-gray-200">
                                                <img src="{{ asset('storage/' . $picture) }}" alt="Product {{ $index + 1 }}" class="w-full h-full object-cover">
                                            </div>
                                        </div>

                                        <!-- File Info -->
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $fileName }}</p>
                                            <p class="text-xs text-gray-500">{{ $fileSizeFormatted }}</p>
                                        </div>

                                        <!-- View Button -->
                                        <a href="{{ asset('storage/' . $picture) }}" target="_blank" class="flex-shrink-0 ml-3 text-[#ff7700] hover:text-[#cc5f00] transition-colors">
                                            <i class="fas fa-external-link-alt text-lg"></i>
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Requested Amount -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Booking Price</label>
                            <span class="text-2xl font-semibold text-[#ff7700]">{{ formatRupiah($booking->total_price) }}</span>
                        </div>

                        <!-- Action Buttons -->
                        @if($booking->status === 'pending')
                        <!-- Buttons View -->
                        <div id="actionButtons" class="flex gap-3">
                            <form method="POST" action="{{ route('booking-requests.confirm', ['event' => $event->id, 'booking' => $booking->id]) }}" onsubmit="return confirm('Confirm this booking request?');">
                                @csrf
                                <button type="submit" class="hover:cursor-pointer bg-green-500 hover:bg-green-600 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200">
                                    Confirm Request
                                </button>
                            </form>
                            <button type="button" onclick="showRejectForm()" class="hover:cursor-pointer bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200">
                                Reject Request
                            </button>
                        </div>

                        <!-- Rejection Form (Hidden by default) -->
                        <div id="rejectForm" class="hidden">
                            <form method="POST" action="{{ route('booking-requests.reject', ['event' => $event->id, 'booking' => $booking->id]) }}">
                                @csrf

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
                                        placeholder="Please provide a clear reason for rejecting this booking request (minimum 10 characters)..."
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
                        @if($booking->status === 'rejected' && $booking->rejection_reason)
                        <!-- Rejection Details -->
                        <div class="mt-6 p-4 bg-red-50 rounded-lg border border-red-200">
                            <h3 class="text-sm font-semibold text-red-900 mb-2">
                                <i class="fas fa-exclamation-circle mr-2"></i>Rejection Reason
                            </h3>
                            <p class="text-sm text-red-800 mb-2">{{ $booking->rejection_reason }}</p>
                            @if($booking->rejected_at)
                            <p class="text-xs text-red-600 mt-2">
                                <i class="fas fa-clock mr-1"></i>
                                Rejected on {{ $booking->rejected_at->format('d M Y, H:i') }}
                            </p>
                            @endif
                        </div>
                        @endif
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

                            <!-- Tenant Rating Display -->
                            <div class="flex items-center mt-3">
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <=floor($averageRating))
                                        <i class="fas fa-star text-[#ff7700] text-sm"></i>
                                        @elseif($i - $averageRating < 1 && $i - $averageRating> 0)
                                            <i class="fas fa-star-half-alt text-[#ff7700] text-sm"></i>
                                            @else
                                            <i class="far fa-star text-gray-300 text-sm"></i>
                                            @endif
                                            @endfor
                                </div>
                                <span class="ml-2 text-sm text-gray-600">
                                    {{ $averageRating > 0 ? number_format($averageRating, 1) : 'No ratings' }}
                                    @if($totalRatings > 0)
                                    ({{ $totalRatings }} {{ $totalRatings === 1 ? 'rating' : 'ratings' }})
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Booth</span>
                            <span class="font-medium">{{ $booking->booth->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Floor no.</span>
                            <span class="font-medium">{{ $booking->booth->floor_number ?? 'N/A' }}</span>
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

                    <!-- Tenant Ratings from Other Organizers -->
                    @if($tenantRatings->count() > 0)
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">
                            Ratings from Other Organizers
                        </h3>

                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            @foreach($tenantRatings->take(5) as $rating)
                            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex items-center">
                                        <div class="flex">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <=$rating->rating)
                                                <i class="fas fa-star text-[#ff7700] text-xs"></i>
                                                @else
                                                <i class="far fa-star text-gray-300 text-xs"></i>
                                                @endif
                                                @endfor
                                        </div>
                                        <span class="ml-2 text-xs font-medium text-gray-700">{{ $rating->rating }}/5</span>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $rating->created_at->format('M d, Y') }}</span>
                                </div>
                                @if($rating->feedback)
                                <p class="text-xs text-gray-600 italic mb-1">"{{ Str::limit($rating->feedback, 100) }}"</p>
                                @endif
                                <p class="text-xs text-gray-500">Event: {{ $rating->event->title ?? 'N/A' }}</p>
                            </div>
                            @endforeach

                            @if($tenantRatings->count() > 5)
                            <p class="text-xs text-gray-500 text-center pt-2">
                                And {{ $tenantRatings->count() - 5 }} more reviews...
                            </p>
                            @endif
                        </div>
                    </div>
                    @endif
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