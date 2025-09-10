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

<body class="bg-gray-50 min-h-screen font-['Instrument_Sans']">
    <!-- Navbar -->
    @include('components.navbar')

    <!-- Main Content -->
    <div class="min-h-screen py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            @include('components.back-button', ['url' => '/my-bookings/details', 'text' => 'Back to Booking Details'])

            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Request Refund</h1>
                <p class="text-gray-600">Booking ID: ID-618261</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Refund Request Form -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Refund Request Details</h2>
                    <p class="text-sm text-gray-600 mb-6">Please provide a reason for your refund request and bank details</p>

                    <form class="space-y-6">
                        <!-- Account Holder Name -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Account Holder Name</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-transparent" placeholder="Enter account holder name">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bank Name</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-transparent" placeholder="Enter bank name">
                            </div>
                        </div>

                        <!-- Account Number -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Account Number</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-transparent" placeholder="Enter account number">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Additional Document (Optional)</label>
                                <div class="relative">
                                    <input type="file" id="fileUpload" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                                    <button type="button" onclick="document.getElementById('fileUpload').click()" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-left text-gray-500 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-transparent">
                                        Choose File
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Refund Reason -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Refund Reason</label>
                            <textarea rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-transparent resize-none" placeholder="Please explain why you need a refund..."></textarea>
                        </div>

                        <!-- Refund Policy -->
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-orange-800 mb-2">Refund Policy</h4>
                            <p class="text-xs text-orange-700 leading-relaxed">
                                Refunds are subject to event organizer approval. Processing may take 5-10 business days. Cancellations within 30 days of the event may incur additional fees.
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-[#ff7700] hover:bg-orange-600 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                            Submit Refund Request
                        </button>
                    </form>
                </div>

                <!-- Booking Summary -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Booking Summary</h2>

                    <div class="space-y-4 mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Tech Innovation Expo 2025</h3>
                            <p class="text-gray-600">Jakarta Convention Center</p>
                            <p class="text-gray-600">19 - 28 October 2025</p>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Booking Date</span>
                            <span class="font-medium">01-11-2025</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Event Date</span>
                            <span class="font-medium">08-11-2025</span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Original Amount</span>
                            <span class="font-medium">Rp.500.000</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Processing Fee</span>
                            <span class="font-medium text-red-600">-Rp.150.000</span>
                        </div>
                        <div class="border-t pt-3">
                            <div class="flex justify-between text-lg font-semibold">
                                <span>Refund Amount</span>
                                <span class="text-[#ff7700]">Rp.350.000</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                        <p class="text-xs text-orange-700">
                            * Final refund amount subject to event organizer approval and refund policy
                        </p>
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
        document.getElementById('fileUpload').addEventListener('change', function(e) {
            const button = e.target.previousElementSibling;
            if (e.target.files.length > 0) {
                button.textContent = e.target.files[0].name;
                button.classList.remove('text-gray-500');
                button.classList.add('text-gray-900');
            } else {
                button.textContent = 'Choose File';
                button.classList.remove('text-gray-900');
                button.classList.add('text-gray-500');
            }
        });

        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();

            alert('Refund request submitted successfully! You will receive an email confirmation shortly.');
        });
    </script>
</body>

</html>