<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Complete Your Data</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    @viteCss
    @viteJs
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="flex items-center justify-center min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <div class="bg-white rounded-lg border border-gray-200 p-8 shadow-sm">
                <div class="mb-8">
                    <h2 class="text-center text-4xl font-bold text-gray-900">
                        Complete Your Profile
                    </h2>
                    <p class="text-center text-gray-600 mt-2">
                        Please provide additional information to complete your account setup
                    </p>
                </div>

                <div class="flex mb-6">
                    <button
                        type="button"
                        id="tenant-tab"
                        class="hover:cursor-pointer flex-1 py-1 px-4 text-center font-medium rounded-l-lg border border-gray-300 bg-white text-gray-700 tab-button"
                        onclick="switchTab('tenant')">
                        I'm a Tenant
                    </button>
                    <button
                        type="button"
                        id="organizer-tab"
                        class="hover:cursor-pointer flex-1 py-1 px-4 text-center font-medium rounded-r-lg border border-l-0 border-gray-300 bg-white text-gray-700 tab-button"
                        onclick="switchTab('event_organizer')">
                        I'm an Event Organizer
                    </button>
                </div>

                <form class="space-y-4" method="POST" action="{{ route('onboarding.save') }}">
                    @csrf
                    <input type="hidden" name="user_type" id="user_type" value="{{ old('user_type', 'tenant') }}">

                    <div>
                        <input
                            type="text"
                            name="business_name"
                            id="business_name"
                            class="block w-full border @error('business_name') @else border-gray-300 @enderror rounded-lg px-3 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700]"
                            placeholder="Business Name"
                            value=""
                            required>
                        @error('business_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative">
                        <div class="flex">
                            <div class="flex items-center px-3 py-3 border border-r-0 border-gray-300 rounded-l-lg bg-gray-50 text-gray-700 font-medium">
                                +62
                            </div>
                            <input
                                type="tel"
                                name="phone_number"
                                id="phone_number"
                                class="block w-full border border-l-0 @error('phone_number') @else border-gray-300 @enderror rounded-r-lg px-3 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700]"
                                placeholder="812-3456-7890"
                                value="{{ old('phone_number') }}"
                                maxlength="15"
                                oninput="formatPhoneInput(this)"
                                required>
                        </div>
                        @error('phone_number')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative">
                        <select
                            name="category_id"
                            id="category_id"
                            class="block w-full border @error('category_id') @else border-gray-300 @enderror rounded-lg px-3 bg-white py-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] appearance-none"
                            required>
                            <option value="" id="category-placeholder" disabled {{ old('category_id', $user->category_id) ? '' : 'selected' }}>Choose a Business Category</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $user->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 mr-2 text-gray-700">
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                        @error('category_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4">
                        <button
                            type="submit"
                            class="w-full bg-[#ff7700] hover:bg-[#e66600] hover:cursor-pointer text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 text-lg">
                            Complete Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function formatPhoneInput(input) {
            // Get only digits from the input
            let digits = input.value.replace(/\D/g, '');

            // Limit to reasonable phone number length (without country code)
            if (digits.length > 12) {
                digits = digits.substring(0, 12);
            }

            // Format the number with hyphens
            let formatted = '';
            if (digits.length > 0) {
                // First block: 3 digits (or less if shorter)
                formatted = digits.substring(0, 3);

                if (digits.length > 3) {
                    // Second block: next 4 digits
                    formatted += '-' + digits.substring(3, 7);
                }

                if (digits.length > 7) {
                    // Third block: remaining digits (up to 4)
                    formatted += '-' + digits.substring(7);
                }
            }

            // Update the input value with formatted version
            input.value = formatted;
        }

        function switchTab(type) {
            const tenantTab = document.getElementById('tenant-tab');
            const organizerTab = document.getElementById('organizer-tab');
            const userTypeInput = document.getElementById('user_type');
            const businessNameInput = document.getElementById('business_name');
            const categoryPlaceholder = document.querySelector('#business_category option[disabled]');

            // Reset styles
            tenantTab.classList.remove('active');
            organizerTab.classList.remove('active');

            if (type === 'tenant') {
                tenantTab.classList.add('active');
                businessNameInput.placeholder = 'Business Name';
                if (categoryPlaceholder) categoryPlaceholder.textContent = 'Choose a Business Category';
                userTypeInput.value = 'tenant';
            } else { // event_organizer
                organizerTab.classList.add('active');
                businessNameInput.placeholder = 'Organization Name';
                if (categoryPlaceholder) categoryPlaceholder.textContent = 'Event Category';
                userTypeInput.value = 'event_organizer';
            }
        }

        // Initialize tabs and fields on page load
        document.addEventListener('DOMContentLoaded', function() {
            const userType = document.getElementById('user_type').value;
            switchTab(userType);

            // Format phone number on page load if there's an old value
            const phoneInput = document.getElementById('phone_number');
            if (phoneInput.value) {
                formatPhoneInput(phoneInput);
            }
        });
    </script>

    <style>
        .tab-button.active {
            background-color: #ff7700;
            border-color: #ff7700;
            color: white;
            cursor: default;
        }

        select:required:invalid {
            color: #6b7280;
            /* placeholder-gray-500 */
        }
    </style>
</body>

</html>