<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create New Event - BoothEase</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }

        .step-connector {
            background: linear-gradient(to right, #d1d5db 0%, #d1d5db 50%, transparent 50%);
        }

        .step-connector.active {
            background: linear-gradient(to right, #ff7700 0%, #ff7700 50%, #d1d5db 50%);
        }

        .step-connector.completed {
            background: linear-gradient(to right, #ff7700 0%, #ff7700 100%);
        }

        .step-connector:last-child {
            background: none !important;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <!-- Navbar -->
    <!-- Footer -->
    @include('components.navbar')

    <!-- Main Content -->
    <div class="min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('components.back-button', ['text' => 'Back to My Events', 'url' => route('my-events')])

            <!-- Header Section -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Create New Event</h1>
                    <p class="text-gray-600 mt-1">Set up your event details and configure booth options</p>
                </div>
            </div>

            <!-- Progress Stepper -->
            @include('components.stepper', ['steps' => [
            ['title' => 'Event Details', 'subtitle' => 'Basic information'],
            ['title' => 'Booth Setup', 'subtitle' => 'Configure booths'],
            ['title' => 'Review & Publish', 'subtitle' => 'Final preview'],
            ]])

            <!-- Form Content -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <form id="createEventForm">
                    <!-- Step 1: Event Details -->
                    <div class="step p-8" data-step="0">
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">Event Details</h2>
                            <p class="text-gray-600">Tell us about your event and where it will take place</p>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Event Title *</label>
                                <input type="text" name="title" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none" placeholder="Enter your event title">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none" placeholder="Describe your event, target audience, and key highlights..."></textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                                    <select name="category" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none">
                                        <option value="">Select category</option>
                                        <option value="Technology">Technology</option>
                                        <option value="Marketing">Marketing</option>
                                        <option value="Food & Beverage">Food & Beverage</option>
                                        <option value="Fashion">Fashion</option>
                                        <option value="Business">Business</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Expected Capacity</label>
                                    <input type="number" name="capacity" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none" placeholder="Number of attendees">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Venue Name *</label>
                                    <input type="text" name="venue" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none" placeholder="e.g. Jakarta Convention Center">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                                    <input type="text" name="city" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none" placeholder="e.g. Jakarta">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Full Address</label>
                                <input type="text" name="address" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none" placeholder="Complete venue address">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                                    <input type="date" name="start_date" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date *</label>
                                    <input type="date" name="end_date" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                                    <input type="time" name="start_time" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                                    <input type="time" name="end_time" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Registration Deadline</label>
                                <input type="date" name="registration_deadline" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Booth Configuration -->
                    <div class="step hidden p-8" data-step="1">
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">Booth Configuration</h2>
                            <p class="text-gray-600">Set up booth types, sizes, and pricing for your event</p>
                        </div>

                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Standard Booth -->
                                <div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-6 hover:border-[#ff7700] transition-colors duration-200">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="font-semibold text-gray-900">Standard Booth</h3>
                                        <i class="fas fa-store text-[#ff7700]"></i>
                                    </div>
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 uppercase tracking-wide mb-1">Size</label>
                                            <input type="text" name="booth_standard_size" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none" placeholder="e.g. 3x3m">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 uppercase tracking-wide mb-1">Price (IDR)</label>
                                            <input type="number" name="booth_standard_price" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none" placeholder="500000">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 uppercase tracking-wide mb-1">Quantity</label>
                                            <input type="number" name="booth_standard_qty" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none" placeholder="50">
                                        </div>
                                    </div>
                                </div>

                                <!-- Premium Booth -->
                                <div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-6 hover:border-[#ff7700] transition-colors duration-200">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="font-semibold text-gray-900">Premium Booth</h3>
                                        <i class="fas fa-crown text-[#ff7700]"></i>
                                    </div>
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 uppercase tracking-wide mb-1">Size</label>
                                            <input type="text" name="booth_premium_size" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none" placeholder="e.g. 4x4m">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 uppercase tracking-wide mb-1">Price (IDR)</label>
                                            <input type="number" name="booth_premium_price" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none" placeholder="1000000">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 uppercase tracking-wide mb-1">Quantity</label>
                                            <input type="number" name="booth_premium_qty" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none" placeholder="25">
                                        </div>
                                    </div>
                                </div>

                                <!-- VIP Booth -->
                                <div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-6 hover:border-[#ff7700] transition-colors duration-200">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="font-semibold text-gray-900">VIP Booth</h3>
                                        <i class="fas fa-gem text-[#ff7700]"></i>
                                    </div>
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 uppercase tracking-wide mb-1">Size</label>
                                            <input type="text" name="booth_vip_size" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none" placeholder="e.g. 6x6m">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 uppercase tracking-wide mb-1">Price (IDR)</label>
                                            <input type="number" name="booth_vip_price" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none" placeholder="2000000">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 uppercase tracking-wide mb-1">Quantity</label>
                                            <input type="number" name="booth_vip_qty" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none" placeholder="10">
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>

                    <!-- Step 3: Preview & Publish -->
                    <div class="step hidden p-8" data-step="2">
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">Review & Publish</h2>
                            <p class="text-gray-600">Review your event details before publishing</p>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-6">
                            <div class="p-6 border-b border-gray-100">
                                <div class="flex justify-between items-start mb-3">
                                    <h3 id="preview-title" class="text-lg font-semibold text-gray-900 leading-tight">Event Title Preview</h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Draft
                                    </span>
                                </div>
                                <p id="preview-category" class="text-sm text-gray-600 mb-1">Category</p>
                            </div>

                            <div class="p-6">
                                <div class="space-y-3 mb-4">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-map-marker-alt mr-2 text-[#ff7700] w-4"></i>
                                        <span id="preview-location">Location</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-calendar-alt mr-2 text-[#ff7700] w-4"></i>
                                        <span id="preview-dates">Event dates</span>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                    <h4 class="font-medium text-gray-900 mb-2">Booth Configuration</h4>
                                    <div id="preview-booths" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        <!-- Booth previews will be inserted here -->
                                    </div>
                                </div>

                                <div class="prose max-w-none">
                                    <h4 class="font-medium text-gray-900 mb-2">Description</h4>
                                    <p id="preview-description" class="text-sm text-gray-700">Event description will appear here...</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center">
                                <input type="checkbox" id="confirm_terms" name="confirm_terms" class="w-4 h-4 text-[#ff7700] bg-gray-100 border-gray-300 rounded focus:ring-[#ff7700] focus:ring-2">
                                <label for="confirm_terms" class="ml-2 text-sm text-gray-900">
                                    I confirm that all event details are accurate and I'm ready to publish this event
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="bg-gray-50 px-8 py-6 border-t border-gray-200 flex items-center justify-between">
                        <button type="button" id="prevBtn" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-3 px-6 rounded-lg transition-colors duration-200" onclick="prevStep()" disabled>
                            <i class="fas fa-arrow-left mr-2"></i>
                            Previous
                        </button>
                        <div class="flex gap-3">
                            <button type="button" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-3 px-6 rounded-lg transition-colors duration-200">
                                Save Draft
                            </button>
                            <button type="button" id="nextBtn" class="bg-[#ff7700] hover:bg-orange-600 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200" onclick="nextStep()">
                                Next: Configure Booths
                                <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                            <button type="submit" id="publishBtn" class="bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 hidden">
                                <i class="fas fa-rocket mr-2"></i>
                                Publish Event
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('components.footer')

    <script>
        let currentStep = 0;
        const steps = document.querySelectorAll('.step');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const publishBtn = document.getElementById('publishBtn');

        function updateStepperUI(stepIndex) {
            for (let i = 1; i <= 3; i++) {
                const stepEl = document.getElementById(`step-${i}`);
                const connectorEl = document.getElementById(`connector-${i}`);

                if (i <= stepIndex + 1) {
                    // Active or completed step
                    stepEl.classList.remove('bg-gray-200', 'text-gray-600');
                    stepEl.classList.add('bg-[#ff7700]', 'text-white');

                    if (i < stepIndex + 1) {
                        // Completed step - show checkmark
                        stepEl.innerHTML = '<i class="fas fa-check"></i>';
                    } else {
                        // Current step - show number
                        stepEl.textContent = i;
                    }
                } else {
                    // Future step
                    stepEl.classList.remove('bg-[#ff7700]', 'text-white');
                    stepEl.classList.add('bg-gray-200', 'text-gray-600');
                    stepEl.textContent = i;
                }

                // Update connectors
                if (connectorEl) {
                    connectorEl.classList.remove('active', 'completed');
                    if (i < stepIndex + 1) {
                        connectorEl.classList.add('completed');
                    } else if (i === stepIndex + 1) {
                        connectorEl.classList.add('active');
                    }
                }
            }
        }

        function showStep(index) {
            currentStep = index;

            steps.forEach((step, i) => {
                if (i === index) {
                    step.classList.remove('hidden');
                } else {
                    step.classList.add('hidden');
                }
            });

            updateStepperUI(index);

            prevBtn.disabled = index === 0;
            prevBtn.classList.toggle('opacity-50', index === 0);
            prevBtn.classList.toggle('cursor-not-allowed', index === 0);

            if (index === steps.length - 1) {
                nextBtn.classList.add('hidden');
                publishBtn.classList.remove('hidden');
            } else {
                nextBtn.classList.remove('hidden');
                publishBtn.classList.add('hidden');
            }

            const nextTexts = [
                'Next: Configure Booths',
                'Next: Review & Publish',
                ''
            ];
            nextBtn.innerHTML = `${nextTexts[index]} <i class="fas fa-arrow-right ml-2"></i>`;
        }

        function nextStep() {
            if (currentStep === 1) updatePreview();
            if (currentStep < steps.length - 1) {
                showStep(currentStep + 1);
            }
        }

        function prevStep() {
            if (currentStep > 0) {
                showStep(currentStep - 1);
            }
        }

        function updatePreview() {
            const form = document.getElementById('createEventForm');
            const data = new FormData(form);

            // Update preview content
            document.getElementById('preview-title').textContent = data.get('title') || 'Event Title Preview';
            document.getElementById('preview-category').textContent = data.get('category') || 'Category';

            const startDate = data.get('start_date') || '';
            const endDate = data.get('end_date') || '';
            document.getElementById('preview-dates').textContent = startDate && endDate ?
                `${startDate} - ${endDate}` : 'Event dates';

            const venue = data.get('venue') || '';
            const city = data.get('city') || '';
            document.getElementById('preview-location').textContent = venue && city ?
                `${venue}, ${city}` : 'Location';

            document.getElementById('preview-description').textContent =
                data.get('description') || 'Event description will appear here...';

            // Update booth preview
            const boothsContainer = document.getElementById('preview-booths');
            boothsContainer.innerHTML = '';

            const boothTypes = ['standard', 'premium', 'vip'];
            const boothLabels = ['Standard', 'Premium', 'VIP'];
            const boothIcons = ['fas fa-store', 'fas fa-crown', 'fas fa-gem'];

            boothTypes.forEach((type, index) => {
                const size = data.get(`booth_${type}_size`);
                const price = data.get(`booth_${type}_price`);
                const qty = data.get(`booth_${type}_qty`);

                if (size || price || qty) {
                    const boothDiv = document.createElement('div');
                    boothDiv.className = 'text-xs p-2 bg-white border border-gray-200 rounded';
                    boothDiv.innerHTML = `
                        <div class="flex items-center mb-1">
                            <i class="${boothIcons[index]} text-[#ff7700] mr-1"></i>
                            <span class="font-medium">${boothLabels[index]}</span>
                        </div>
                        <div class="text-gray-600">
                            ${size ? `Size: ${size}` : ''}<br>
                            ${price ? `Price: Rp ${parseInt(price).toLocaleString()}` : ''}<br>
                            ${qty ? `Qty: ${qty}` : ''}
                        </div>
                    `;
                    boothsContainer.appendChild(boothDiv);
                }
            });

            if (boothsContainer.children.length === 0) {
                boothsContainer.innerHTML = '<p class="text-gray-500 text-sm col-span-3">No booth configuration set</p>';
            }
        }

        function validateStep(stepIndex) {
            const form = document.getElementById('createEventForm');
            const currentStepEl = steps[stepIndex];
            const requiredFields = currentStepEl.querySelectorAll('input[required], select[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('border-red-500');
                    isValid = false;
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            return isValid;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const requiredFields = [
                'input[name="title"]',
                'select[name="category"]',
                'input[name="venue"]',
                'input[name="city"]',
                'input[name="start_date"]',
                'input[name="end_date"]'
            ];

            requiredFields.forEach(selector => {
                const field = document.querySelector(selector);
                if (field) {
                    field.setAttribute('required', 'required');
                }
            });

            showStep(0);
        });

        function nextStep() {
            if (!validateStep(currentStep)) {
                const alertDiv = document.createElement('div');
                alertDiv.className = 'bg-red-50 border border-red-200 rounded-lg p-4 mb-4';
                alertDiv.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                        <span class="text-red-700 text-sm">Please fill in all required fields before continuing.</span>
                    </div>
                `;

                const currentStepEl = steps[currentStep];
                currentStepEl.insertBefore(alertDiv, currentStepEl.firstChild);

                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.remove();
                    }
                }, 5000);

                return;
            }

            if (currentStep === 1) updatePreview();
            if (currentStep < steps.length - 1) {
                showStep(currentStep + 1);
            }
        }

        document.getElementById('createEventForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const confirmCheckbox = document.getElementById('confirm_terms');
            if (!confirmCheckbox.checked) {
                alert('Please confirm that all event details are accurate before publishing.');
                return;
            }

            // Simulate API call
            const publishButton = document.getElementById('publishBtn');
            const originalText = publishButton.innerHTML;

            publishButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Publishing...';
            publishButton.disabled = true;

            setTimeout(() => {
                alert('Event published successfully! 🎉');
                publishButton.innerHTML = originalText;
                publishButton.disabled = false;
            }, 2000);
        });

        document.addEventListener('input', function(e) {
            if (currentStep === 2) {
                updatePreview();
            }
        });

        // Add smooth transitions
        const style = document.createElement('style');
        style.textContent = `
            .step {
                opacity: 1;
                transform: translateX(0);
                transition: all 0.3s ease-in-out;
            }
            .step.hidden {
                opacity: 0;
                transform: translateX(20px);
            }
        `;
        document.head.appendChild(style);
    </script>
</body>

</html>