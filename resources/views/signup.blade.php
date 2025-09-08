<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up - {{ config('app.name', 'BoothEase') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen">
    <!-- Navbar -->
    @include('components.navbar')

    <!-- Content Container with Back Button -->
    <div class="relative">
        <!-- Back Button -->
        @include('components.back-button')

        <!-- Sign Up Form -->
        <div class="flex items-center justify-center min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full">
                <div class="bg-white rounded-lg border border-gray-200 p-8 shadow-sm">
                    <div class="mb-8">
                        <h2 class="text-center text-4xl font-bold text-gray-900">
                            Sign Up
                        </h2>
                    </div>

                    <!-- Tab Buttons -->
                    <div class="flex mb-6">
                        <button
                            type="button"
                            id="tenant-tab"
                            class="hover:cursor-pointer flex-1 py-1 px-4 text-center font-medium rounded-l-lg border border-gray-300 bg-white text-gray-700 tab-button active"
                            onclick="switchTab('tenant')">
                            I'm a Tenant
                        </button>
                        <button
                            type="button"
                            id="organizer-tab"
                            class="hover:cursor-pointer flex-1 py-1 px-4 text-center font-medium rounded-r-lg border border-l-0 border-gray-300 bg-white text-gray-700 tab-button"
                            onclick="switchTab('organizer')">
                            I'm an Event Organizer
                        </button>
                    </div>

                    <form class="space-y-4" action="#" method="POST">
                        @csrf
                        <input type="hidden" name="user_type" id="user_type" value="tenant">

                        <!-- Full Name -->
                        <div>
                            <input
                                type="text"
                                name="full_name"
                                id="full_name"
                                class="block w-full border border-gray-300 rounded-lg px-3 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700]"
                                placeholder="Full Name"
                                required>
                        </div>

                        <!-- Business Name -->
                        <div>
                            <input
                                type="text"
                                name="business_name"
                                id="business_name"
                                class="block w-full border border-gray-300 rounded-lg px-3 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700]"
                                placeholder="Business Name"
                                required>
                        </div>

                        <!-- Email -->
                        <div>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="block w-full border border-gray-300 rounded-lg px-3 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700]"
                                placeholder="Email"
                                required>
                        </div>

                        <!-- Mobile Number -->
                        <div>
                            <div class="flex border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-[#ff7700] focus-within:border-[#ff7700] transition-all duration-200">
                                <!-- Country Code Dropdown -->
                                <div class="relative">
                                    <select name="country_code" class="appearance-none bg-white border-0 rounded-l-lg px-3 py-3 pr-8 text-gray-700 focus:outline-none focus:ring-0">
                                        <option value="+62">🇮🇩 +62</option>
                                        <option value="+1">🇺🇸 +1</option>
                                        <option value="+44">🇬🇧 +44</option>
                                        <option value="+81">🇯🇵 +81</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                        </svg>
                                    </div>
                                </div>
                                <!-- Mobile Number Input -->
                                <input
                                    type="tel"
                                    name="mobile_number"
                                    id="mobile_number"
                                    class="flex-1 block w-full border-0 border-l border-gray-300 rounded-r-lg px-3 py-3 placeholder-gray-400 focus:outline-none focus:ring-0"
                                    placeholder="Mobile Number"
                                    required>
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="block w-full border border-gray-300 rounded-lg px-3 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700]"
                                placeholder="Password"
                                required>
                        </div>

                        <!-- Business Category (only for organizers) -->
                        <div id="business-category-field" class="hidden">
                            <select
                                name="business_category"
                                id="business_category"
                                class="block w-full border border-gray-300 rounded-lg px-3 py-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] appearance-none"
                                onchange="handleBusinessCategoryChange()">
                                <option value="">Event Category</option>
                                <option value="technology">Technology</option>
                                <option value="healthcare">Healthcare</option>
                                <option value="education">Education</option>
                                <option value="retail">Retail</option>
                                <option value="food-beverage">Food & Beverage</option>
                                <option value="automotive">Automotive</option>
                                <option value="real-estate">Real Estate</option>
                                <option value="finance">Finance</option>
                                <option value="entertainment">Entertainment</option>
                                <option value="other">Other</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700" style="margin-top: -38px; margin-right: 8px;">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Custom Business Category (only when "Other" is selected) -->
                        <div id="custom-business-category-field" class="hidden">
                            <input
                                type="text"
                                name="custom_business_category"
                                id="custom_business_category"
                                class="block w-full border border-gray-300 rounded-lg px-3 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700]"
                                placeholder="Please specify your business category">
                        </div>

                        <!-- Sign Up Button -->
                        <div class="pt-4">
                            <button
                                type="submit"
                                class="w-full bg-[#ff7700] hover:bg-[#e66600] text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 text-lg">
                                Sign Up
                            </button>
                        </div>

                        <!-- Sign In Link -->
                        <div class="text-center pt-2">
                            <span class="text-gray-600">Already have an account?</span>
                            <a href="/login" class="text-[#ff7700] hover:text-[#e66600] font-medium ml-1 transition-colors">
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
            const businessCategoryField = document.getElementById('business-category-field');
            const businessCategorySelect = document.getElementById('business_category');
            const customBusinessCategoryField = document.getElementById('custom-business-category-field');
            const businessNameInput = document.getElementById('business_name');

            if (type === 'tenant') {
                // Activate tenant tab
                tenantTab.classList.add('active');
                tenantTab.classList.remove('bg-gray-100');
                organizerTab.classList.remove('active');
                organizerTab.classList.add('bg-gray-100');

                // Hide business category fields
                businessCategoryField.classList.add('hidden');
                customBusinessCategoryField.classList.add('hidden');
                businessCategorySelect.removeAttribute('required');

                // Change placeholder to Business Name
                businessNameInput.placeholder = 'Business Name';

                userTypeInput.value = 'tenant';
            } else {
                // Activate organizer tab
                organizerTab.classList.add('active');
                organizerTab.classList.remove('bg-gray-100');
                tenantTab.classList.remove('active');
                tenantTab.classList.add('bg-gray-100');

                // Show business category field
                businessCategoryField.classList.remove('hidden');
                businessCategorySelect.setAttribute('required', 'required');

                // Change placeholder to Organization Name
                businessNameInput.placeholder = 'Organization Name';

                userTypeInput.value = 'organizer';
            }
        }

        function handleBusinessCategoryChange() {
            const businessCategorySelect = document.getElementById('business_category');
            const customBusinessCategoryField = document.getElementById('custom-business-category-field');
            const customBusinessCategoryInput = document.getElementById('custom_business_category');

            if (businessCategorySelect.value === 'other') {
                // Show custom business category field
                customBusinessCategoryField.classList.remove('hidden');
                customBusinessCategoryInput.setAttribute('required', 'required');
            } else {
                // Hide custom business category field
                customBusinessCategoryField.classList.add('hidden');
                customBusinessCategoryInput.removeAttribute('required');
                customBusinessCategoryInput.value = ''; // Clear the input
            }
        }

        // Initialize with tenant tab active
        document.addEventListener('DOMContentLoaded', function() {
            switchTab('tenant');
        });
    </script>

    <style>
        .tab-button.active {
            background-color: #ff7700;
            border-color: #ff7700;
            color: white;
        }
    </style>
</body>

</html>