<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen">
    @include('components.navbar')

    <div class="pt-2">

        <div class="flex items-center justify-center min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full">
                <div class="bg-white rounded-lg border border-gray-200 p-8 shadow-sm">
                    <div class="mb-8">
                        <h2 class="text-center text-4xl font-bold text-gray-900">
                            Sign Up
                        </h2>
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

                    <form class="space-y-4" action="{{ route('signup') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_type" id="user_type" value="{{ old('user_type', 'tenant') }}">

                        <div>
                            <input
                                type="text"
                                name="full_name"
                                id="full_name"
                                class="block w-full border @error('full_name') @else border-gray-300 @enderror rounded-lg px-3 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700]"
                                placeholder="Full Name"
                                value="{{ old('full_name') }}"
                                required>
                            @error('full_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <input
                                type="text"
                                name="business_name"
                                id="business_name"
                                class="block w-full border @error('business_name')@else border-gray-300 @enderror rounded-lg px-3 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700]"
                                placeholder="Business Name"
                                value="{{ old('business_name') }}"
                                required>
                            @error('business_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="block w-full border @error('email') @else border-gray-300 @enderror rounded-lg px-3 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700]"
                                placeholder="Email"
                                value="{{ old('email') }}"
                                required>
                            @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="relative">
                            <div class="flex">
                                <div class="flex items-center px-3 py-border border-r-0 border-gray-300 rounded-l-lg text-gray-700 font-medium">
                                    +62
                                </div>
                                <input
                                    type="tel"
                                    name="phone_number"
                                    id="phone_number"
                                    class="block w-full border @error('phone_number')@else border-gray-300 @enderror rounded-lg px-3 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700]"
                                    placeholder="Mobile Number"
                                    value="{{ old('phone_number') }}"
                                    required>
                                @error('phone_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="block w-full border @error('password')@else border-gray-300 @enderror rounded-lg px-3 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700]"
                                placeholder="Password"
                                required>
                            @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="business-category-field" class="relative">
                            <select
                                name="business_category"
                                id="business_category"
                                class="block w-full border @error('business_category')@else border-gray-300 @enderror rounded-lg px-3 bg-white py-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] appearance-none"
                                onchange="handleBusinessCategoryChange()"
                                required>
                                <option value="" id="category-placeholder" disabled {{ old('business_category') ? '' : 'selected' }}>Choose a Business Category</option>
                                <option value="technology" {{ old('business_category') == 'technology' ? 'selected' : '' }}>Technology</option>
                                <option value="healthcare" {{ old('business_category') == 'healthcare' ? 'selected' : '' }}>Healthcare</option>
                                <option value="education" {{ old('business_category') == 'education' ? 'selected' : '' }}>Education</option>
                                <option value="retail" {{ old('business_category') == 'retail' ? 'selected' : '' }}>Retail</option>
                                <option value="food-beverage" {{ old('business_category') == 'food-beverage' ? 'selected' : '' }}>Food & Beverage</option>
                                <option value="automotive" {{ old('business_category') == 'automotive' ? 'selected' : '' }}>Automotive</option>
                                <option value="real-estate" {{ old('business_category') == 'real-estate' ? 'selected' : '' }}>Real Estate</option>
                                <option value="finance" {{ old('business_category') == 'finance' ? 'selected' : '' }}>Finance</option>
                                <option value="entertainment" {{ old('business_category') == 'entertainment' ? 'selected' : '' }}>Entertainment</option>
                                <option value="other" {{ old('business_category') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 mr-2 text-gray-700">
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                            @error('business_category')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="custom-business-category-field" class="{{ old('business_category') == 'other' ? '' : 'hidden' }}">
                            <input
                                type="text"
                                name="custom_business_category"
                                id="custom_business_category"
                                class="block w-full border @error('custom_business_category') @else border-gray-300 @enderror rounded-lg px-3 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700]"
                                placeholder="Please specify your business category"
                                value="{{ old('custom_business_category') }}">
                            @error('custom_business_category')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-4">
                            <button
                                type="submit"
                                class="w-full hover:cursor-pointer bg-[#ff7700] hover:bg-[#e66600] text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 text-lg">
                                Sign Up
                            </button>
                        </div>

                        <div class="text-center pt-2">
                            <span class="text-gray-600">Already have an account?</span>
                            <a href="{{ route('login') }}" class="text-[#ff7700] hover:text-[#e66600] font-medium ml-1 transition-colors">
                                Sign In
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
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

        function handleBusinessCategoryChange() {
            const categorySelect = document.getElementById('business_category');
            const customField = document.getElementById('custom-business-category-field');
            const customInput = document.getElementById('custom_business_category');

            if (categorySelect.value === 'other') {
                customField.classList.remove('hidden');
                customInput.setAttribute('required', 'required');
            } else {
                customField.classList.add('hidden');
                customInput.removeAttribute('required');
                customInput.value = ''; // Clear the input
            }
        }

        // Initialize tabs and fields on page load based on old input
        document.addEventListener('DOMContentLoaded', function() {
            const userType = document.getElementById('user_type').value;
            switchTab(userType);
            handleBusinessCategoryChange();
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