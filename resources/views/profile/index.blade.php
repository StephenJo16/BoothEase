<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Profile</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

@php
$categories = ['technology','healthcare','education','retail','food-beverage','automotive','real-estate','finance','entertainment','other'];

$rawCategory = $user->business_category; // what’s stored in DB (could be custom)
$isCustomSaved = $rawCategory && !in_array($rawCategory, $categories, true);

// if validation fails, old() wins; otherwise map custom -> 'other' for the select
$current = old('business_category', $isCustomSaved ? 'other' : $rawCategory);
$customValue = old('custom_business_category', $isCustomSaved ? $rawCategory : '');

// --- role badge text + color ---
$roleMap = [1 => 'Admin', 2 => 'Tenant', 3 => 'Event Organizer'];
$roleLabel = $roleMap[$user->role_id] ?? 'Member';

$roleBadgeClasses = match($user->role_id) {
1 => 'bg-purple-100 text-purple-800',
2 => 'bg-blue-100 text-blue-800',
3 => 'bg-green-100 text-green-800',
default => 'bg-gray-100 text-gray-800',
};
@endphp

<body class="bg-gray-50 min-h-screen">

    <!-- Navbar -->
    @include('components.navbar')

    <div class="flex items-start justify-center min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl w-full">
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                <!-- Header -->
                <div class="border-b border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
                        <button
                            id="edit-btn"
                            class="bg-[#ff7700] hover:bg-[#e66600] text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200"
                            onclick="toggleEditMode()">
                            Edit Profile
                        </button>
                    </div>
                    <!-- User Type Badge -->
                    <div class="mt-3">
                        <span
                            id="user-type-badge"
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $roleBadgeClasses }}">
                            {{ $roleLabel }}
                        </span>
                    </div>
                </div>

                <!-- Profile Content -->
                <div class="p-6">
                    <form id="profile-form" method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <!-- Full Name -->
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <label class="text-sm font-medium text-gray-700 w-full sm:w-1/3 mb-2 sm:mb-0">Full Name</label>
                            <div class="w-full sm:w-2/3">
                                <input id="full_name" name="full_name"
                                    value="{{ old('full_name', $user->display_name) }}"
                                    class=" profile-input block w-full border border-gray-300 rounded-lg px-3 py-3 bg-gray-50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700]"
                                    readonly>
                            </div>
                        </div>

                        <!-- Business/Organization Name -->
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <label id="business-label" class="text-sm font-medium text-gray-700 w-full sm:w-1/3 mb-2 sm:mb-0">Business Name</label>
                            <div class="w-full sm:w-2/3">
                                <input id="business_name" name="business_name"
                                    value="{{ old('business_name', $user->name) }}"
                                    class="profile-input block w-full border border-gray-300 rounded-lg px-3 py-3 bg-gray-50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700]"
                                    readonly>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <label class="text-sm font-medium text-gray-700 w-full sm:w-1/3 mb-2 sm:mb-0">Email</label>
                            <div class="w-full sm:w-2/3">
                                <input id="email" value="{{ $user->email }}"
                                    class="profile-input block w-full border border-gray-300 rounded-lg px-3 py-3 bg-gray-50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700]"
                                    readonly>
                            </div>
                        </div>

                        <!-- Mobile Number -->
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <label class="text-sm font-medium text-gray-700 w-full sm:w-1/3 mb-2 sm:mb-0">Mobile Number</label>
                            <div class="w-full sm:w-2/3">
                                <div class="profile-field-container flex border border-gray-300 rounded-lg bg-gray-50 transition-all duration-200 focus-within:ring-2 focus-within:ring-[#ff7700] focus-within:border-[#ff7700]">
                                    <div class="relative">
                                        <div class="bg-white border-0 rounded-l-lg px-4 py-3 text-gray-700">
                                            +62
                                        </div>
                                    </div>
                                    <input id="mobile_number" name="mobile_number"
                                        value="{{ old('mobile_number', preg_replace('/^\+62/', '', $user->phone_number ?? '')) }}"
                                        class="profile-input flex-1 block w-full border-0 border-l border-gray-300 rounded-r-lg px-3 py-3 bg-gray-50 text-gray-900 focus:outline-none focus:ring-0"
                                        readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Business/Event Category -->
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <label id="category-label" class="text-sm font-medium text-gray-700 w-full sm:w-1/3 mb-2 sm:mb-0">
                                Business Category
                            </label>
                            <div class="w-full sm:w-2/3">
                                <div class="relative">
                                    <select
                                        id="business_category"
                                        name="business_category"
                                        class="profile-select block w-full border border-gray-300 rounded-lg px-3 py-3 bg-gray-50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] appearance-none"
                                        disabled
                                        onchange="handleBusinessCategoryChange()">
                                        @foreach ($categories as $cat)
                                        <option value="{{ $cat }}" @selected($current===$cat)>
                                            {{ ucfirst(str_replace('-', ' ', $cat)) }}
                                        </option>
                                        @endforeach
                                    </select>

                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 mr-2 text-gray-700">
                                        <i class="fa-solid fa-chevron-down"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Custom Business Category (hidden by default) -->
                        <div id="custom-business-category-field" class="{{ ($current === 'other') ? 'flex' : 'hidden' }} flex-col sm:flex-row sm:items-center">
                            <label class="text-sm font-medium text-gray-700 w-full sm:w-1/3 mb-2 sm:mb-0">
                                Custom Category
                            </label>

                            <div class="w-full sm:w-2/3">
                                <input
                                    type="text"
                                    id="custom_business_category"
                                    name="custom_business_category"
                                    value="{{ $customValue }}"
                                    placeholder="Please specify your business category"
                                    class="profile-input block w-full border border-gray-300 rounded-lg px-3 py-3 bg-gray-50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700]"
                                    readonly>
                            </div>
                        </div>


                        <!-- Password Section -->
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Security</h3>
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-center">
                                <div class="w-full sm:w-2/3 flex justify-center">
                                    <button
                                        type="button"
                                        id="change-password-btn"
                                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors duration-200 border border-gray-300"
                                        onclick="togglePasswordChange()">
                                        Change Password
                                    </button>
                                </div>
                            </div>


                            <!-- Password Change Fields (hidden by default) -->
                            <div id="password-change-section" class="hidden mt-4 space-y-4">
                                <div class="flex flex-col sm:flex-row sm:items-center">
                                    <label class="text-sm font-medium text-gray-700 w-full sm:w-1/3 mb-2 sm:mb-0">Current Password</label>
                                    <div class="w-full sm:w-2/3">
                                        <input
                                            type="password"
                                            id="current_password"
                                            name="current_password"
                                            class="block w-full border border-gray-300 rounded-lg px-3 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700]"
                                            placeholder="Enter current password">
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:items-center">
                                    <label class="text-sm font-medium text-gray-700 w-full sm:w-1/3 mb-2 sm:mb-0">New Password</label>
                                    <div class="w-full sm:w-2/3">
                                        <input
                                            type="password"
                                            id="new_password"
                                            name="new_password"
                                            class="block w-full border border-gray-300 rounded-lg px-3 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700]"
                                            placeholder="Enter new password">
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:items-center">
                                    <label class="text-sm font-medium text-gray-700 w-full sm:w-1/3 mb-2 sm:mb-0">Confirm New Password</label>
                                    <div class="w-full sm:w-2/3">
                                        <input
                                            type="password"
                                            id="confirm_password"
                                            name="confirm_password"
                                            class="block w-full border border-gray-300 rounded-lg px-3 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700]"
                                            placeholder="Confirm new password">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons (hidden by default) -->
                        <div id="action-buttons" class="hidden justify-end space-x-3 pt-6 border-t border-gray-200">
                            <button
                                type="button"
                                onclick="cancelEdit()"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors duration-200 border border-gray-300">
                                Cancel
                            </button>
                            <button
                                type="submit"
                                class="bg-[#ff7700] hover:bg-[#e66600] text-white font-medium py-2 px-6 rounded-lg transition-colors duration-200">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Account Information Card -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm mt-6 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Account Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="text-sm font-medium text-gray-500">Member Since</span>
                        <p class="text-gray-900">
                            {{ optional($user->created_at)->timezone('Asia/Jakarta')->format('F j, Y') }}
                        </p>
                    </div>

                    <div>
                        <span class="text-sm font-medium text-gray-500">Account Status</span>
                        <p class="text-green-600 font-medium">Active</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let isEditMode = false;
        let isPasswordChangeMode = false;

        function toggleEditMode() {
            isEditMode = !isEditMode;
            const editBtn = document.getElementById('edit-btn');
            const actionButtons = document.getElementById('action-buttons');
            const profileInputs = document.querySelectorAll('.profile-input');
            const profileSelects = document.querySelectorAll('.profile-select');
            const profileFieldContainers = document.querySelectorAll('.profile-field-container');

            if (isEditMode) {
                editBtn.textContent = 'Cancel Edit';
                editBtn.classList.remove('bg-[#ff7700]', 'hover:bg-[#e66600]');
                editBtn.classList.add('bg-gray-500', 'hover:bg-gray-600');
                actionButtons.classList.remove('hidden');

                // Enable inputs
                profileInputs.forEach(input => {
                    if (input.id !== 'email') { // Keep email readonly for security
                        input.removeAttribute('readonly');
                        input.classList.remove('bg-gray-50');
                        input.classList.add('bg-white');
                    }
                });

                profileSelects.forEach(select => {
                    if (select.id !== 'country_code') { // Keep country code disabled for now
                        select.removeAttribute('disabled');
                    }
                    select.classList.remove('bg-gray-50');
                    select.classList.add('bg-white');
                });

                profileFieldContainers.forEach(container => {
                    container.classList.remove('bg-gray-50');
                    container.classList.add('bg-white');
                });
            } else {
                cancelEdit();
            }
        }


        function togglePasswordChange() {
            isPasswordChangeMode = !isPasswordChangeMode;
            const passwordSection = document.getElementById('password-change-section');
            const changeBtn = document.getElementById('change-password-btn');

            if (isPasswordChangeMode) {
                passwordSection.classList.remove('hidden');
                changeBtn.textContent = 'Cancel';
                changeBtn.classList.remove('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');
                changeBtn.classList.add('bg-red-100', 'hover:bg-red-200', 'text-red-700');
            } else {
                passwordSection.classList.add('hidden');
                changeBtn.textContent = 'Change Password';
                changeBtn.classList.add('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');
                changeBtn.classList.remove('bg-red-100', 'hover:bg-red-200', 'text-red-700');

                // Clear password fields
                document.getElementById('current_password').value = '';
                document.getElementById('new_password').value = '';
                document.getElementById('confirm_password').value = '';
            }
        }

        function handleBusinessCategoryChange() {
            const categorySelect = document.getElementById('business_category');
            const customField = document.getElementById('custom-business-category-field');
            const customInput = document.getElementById('custom_business_category');

            if (categorySelect.value === 'other') {
                customField.classList.remove('hidden');
                if (isEditMode) {
                    customInput.removeAttribute('readonly');
                    customInput.classList.remove('bg-gray-50');
                    customInput.classList.add('bg-white');
                }
            } else {
                customField.classList.add('hidden');
                customInput.value = '';
            }
        }

        function updateUIForUserType(userType) {
            const userTypeBadge = document.getElementById('user-type-badge');
            const businessLabel = document.getElementById('business-label');
            const categoryLabel = document.getElementById('category-label');

            if (userType === 'event_organizer') {
                userTypeBadge.textContent = 'Event Organizer';
                userTypeBadge.classList.remove('bg-blue-100', 'text-blue-800');
                userTypeBadge.classList.add('bg-green-100', 'text-green-800');
                businessLabel.textContent = 'Organization Name';
                categoryLabel.textContent = 'Event Category';
            } else {
                userTypeBadge.textContent = 'Tenant';
                userTypeBadge.classList.remove('bg-green-100', 'text-green-800');
                userTypeBadge.classList.add('bg-blue-100', 'text-blue-800');
                businessLabel.textContent = 'Business Name';
                categoryLabel.textContent = 'Business Category';
            }
        }
    </script>

    <style>
        .profile-input:read-only {
            cursor: default;
        }

        .profile-select:disabled {
            cursor: default;
        }

        .profile-input,
        .profile-select {
            transition: all 0.2s ease;
        }
    </style>
</body>

</html>