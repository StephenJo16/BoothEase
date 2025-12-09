<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale-1">
    <title>My Profile</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @viteCss
    @viteJs
</head>

@php
/* --- This PHP block handles category logic --- */
// Get all category names from the database collection
$categoryNames = $categories->pluck('name')->toArray();

$rawCategory = $user->business_category;
$isCustomSaved = $rawCategory && !in_array($rawCategory, $categoryNames, true);
$current = old('business_category', $isCustomSaved ? 'other' : $rawCategory);
$customValue = old('custom_business_category', $isCustomSaved ? $rawCategory : '');
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

    @if (session('success'))
    <div class="notification-popup success">
        <i class="fa-solid fa-circle-check mr-2"></i>
        {{ session('success') }}
    </div>
    @endif

    {{-- Shows a generic error if validation fails --}}
    @if ($errors->any())
    <div class="notification-popup error">
        <i class="fa-solid fa-triangle-exclamation mr-2"></i>
        Please review the form for errors.
    </div>
    @endif
    @include('components.navbar')

    <div class="flex items-start justify-center min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl w-full">
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                <div class="border-b border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
                        <button id="edit-btn" class="hover:cursor-pointer bg-[#ff7700] hover:bg-[#e66600] text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200" onclick="toggleEditMode()">
                            Edit Profile
                        </button>
                    </div>
                    <div class="mt-3">
                        <span id="user-type-badge" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $roleBadgeClasses }}">
                            {{ $roleLabel }}
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    {{-- Profile Form --}}
                    <form id="profile-form" method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- All your profile form fields go here, no changes needed --}}
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <label class="text-sm font-medium text-gray-700 w-full sm:w-1/3 mb-2 sm:mb-0">Full Name</label>
                            <div class="w-full sm:w-2/3">
                                <input id="full_name" name="full_name" value="{{ old('full_name', $user->display_name) }}" class=" profile-input block w-full border border-gray-300 rounded-lg px-3 py-3 bg-gray-50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700]" readonly>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <label id="business-label" class="text-sm font-medium text-gray-700 w-full sm:w-1/3 mb-2 sm:mb-0">
                                {{ $user->role_id === 3 ? 'Organization Name' : 'Business Name' }}
                            </label>
                            <div class="w-full sm:w-2/3">
                                <input id="business_name" name="business_name" value="{{ old('business_name', $user->name) }}" class="profile-input block w-full border border-gray-300 rounded-lg px-3 py-3 bg-gray-50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700]" readonly>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <label class="text-sm font-medium text-gray-700 w-full sm:w-1/3 mb-2 sm:mb-0">Email</label>
                            <div class="w-full sm:w-2/3">
                                <input id="email" value="{{ $user->email }}" class="profile-input block w-full border border-gray-300 rounded-lg px-3 py-3 bg-gray-50 text-gray-900" readonly>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <label class="text-sm font-medium text-gray-700 w-full sm:w-1/3 mb-2 sm:mb-0">Mobile Number</label>
                            <div class="w-full sm:w-2/3">
                                <div class="profile-field-container flex border border-gray-300 rounded-lg bg-gray-50 transition-all duration-200 focus-within:ring-2 focus-within:ring-[#ff7700] focus-within:border-[#ff7700]">
                                    <span class="inline-flex items-center px-3 border-r border-gray-300 bg-gray-100 text-gray-700 rounded-l-lg">+62</span>
                                    <input id="mobile_number" name="mobile_number" value="{{ old('mobile_number', preg_replace('/^\+?62/', '', $user->phone_number)) }}" class="profile-input hidden flex-1 w-full border-0 rounded-r-lg px-3 py-3 bg-gray-50 text-gray-900 focus:outline-none focus:ring-0" readonly>
                                    <span id="mobile-display" class="flex-1 px-3 py-3 text-gray-900">{{ preg_replace('/^\+?62\s*/', '', $user->phone_number ? formatPhoneNumber($user->phone_number) : 'N/A') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <label id="category-label" class="text-sm font-medium text-gray-700 w-full sm:w-1/3 mb-2 sm:mb-0">
                                {{ $user->role_id === 3 ? 'Event Category' : 'Business Category' }}
                            </label>
                            <div class="w-full sm:w-2/3">
                                <div class="relative">
                                    <select id="business_category" name="business_category" class="profile-select block w-full border border-gray-300 rounded-lg px-3 py-3 bg-gray-50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] appearance-none" disabled onchange="handleBusinessCategoryChange()">
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->name }}" @selected($current===$category->name)>
                                            {{ ucfirst(str_replace('-', ' ', $category->name)) }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                        <i class="fa-solid fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="custom-business-category-field" class="{{ $current === 'other' ? 'flex' : 'hidden' }} flex-col sm:flex-row sm:items-center">
                            <label class="text-sm font-medium text-gray-700 w-full sm:w-1/3 mb-2 sm:mb-0">Custom Category</label>
                            <div class="w-full sm:w-2/3">
                                <input type="text" id="custom_business_category" name="custom_business_category" value="{{ $customValue }}" placeholder="Please specify your business category" class="profile-input block w-full border border-gray-300 rounded-lg px-3 py-3 bg-gray-50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700]" readonly>
                            </div>
                        </div>

                        <div id="action-buttons" class="hidden justify-end space-x-3 pt-6 border-t border-gray-200">
                            <button type="button" onclick="cancelEdit()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors duration-200 border border-gray-300">
                                Cancel
                            </button>
                            <button type="submit" class="bg-[#ff7700] hover:bg-[#e66600] text-white font-medium py-2 px-6 rounded-lg transition-colors duration-200">
                                Save Changes
                            </button>
                        </div>
                    </form>

                    @if(!$user->provider)
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Security</h3>
                        <div class="flex justify-center">
                            <button type="button" id="change-password-btn" class="hover:cursor-pointer bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors duration-200 border border-gray-300" onclick="togglePasswordChange()">
                                Change Password
                            </button>
                        </div>
                        <div id="password-change-section" class="hidden mt-4">
                            <form id="password-form" method="POST" action="{{ route('profile.password') }}" class="space-y-4">
                                @csrf
                                @method('PUT')
                                {{-- Password Fields... --}}
                                <div class="flex flex-col sm:flex-row sm:items-center">
                                    <label for="current_password" class="text-sm font-medium text-gray-700 w-full sm:w-1/3 mb-2 sm:mb-0">Current Password</label>
                                    <div class="w-full sm:w-2/3">
                                        <input type="password" id="current_password" name="current_password" class="block w-full border border-gray-300 rounded-lg px-3 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700]" placeholder="Enter current password" required>
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:items-center">
                                    <label for="new_password" class="text-sm font-medium text-gray-700 w-full sm:w-1/3 mb-2 sm:mb-0">New Password</label>
                                    <div class="w-full sm:w-2/3">
                                        <input type="password" id="new_password" name="new_password" class="block w-full border border-gray-300 rounded-lg px-3 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700]" placeholder="Enter new password (min. 8 characters)" required>
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:items-center">
                                    <label for="new_password_confirmation" class="text-sm font-medium text-gray-700 w-full sm:w-1/3 mb-2 sm:mb-0">Confirm New Password</label>
                                    <div class="w-full sm:w-2/3">
                                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="block w-full border border-gray-300 rounded-lg px-3 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700]" placeholder="Confirm new password" required>
                                    </div>
                                </div>
                                <div class="flex justify-end space-x-3 pt-4">
                                    <button type="button" onclick="togglePasswordChange()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors duration-200 border border-gray-300">
                                        Cancel
                                    </button>
                                    <button type="submit" class="bg-[#ff7700] hover:bg-[#e66600] text-white font-medium py-2 px-6 rounded-lg transition-colors duration-200">
                                        Save Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Account Info Card --}}
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

    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex justify-center items-center mb-6">
                <img src="{{ asset('images/boothease-logo-cropped.png') }}" alt="BoothEase" class="h-10 mr-3">
            </div>
            <p class="text-gray-400 mb-4">Making event booth booking simple and efficient</p>
            <p class="text-sm text-gray-500">All Rights Reserved ©</p>
        </div>
    </footer>
    {{-- Your JavaScript code remains the same --}}
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

            editBtn.classList.toggle('bg-[#ff7700]');
            editBtn.classList.toggle('hover:bg-[#e66600]');
            editBtn.classList.toggle('bg-gray-500');
            editBtn.classList.toggle('hover:bg-gray-600');
            actionButtons.classList.toggle('hidden');
            actionButtons.classList.toggle('flex');

            if (isEditMode) {
                editBtn.textContent = 'Cancel Edit';

                // Toggle mobile number display/edit
                const mobileDisplay = document.getElementById('mobile-display');
                const mobileInput = document.getElementById('mobile_number');
                mobileDisplay.classList.add('hidden');
                mobileInput.classList.remove('hidden');

                profileInputs.forEach(input => {
                    if (input.id !== 'email') {
                        input.removeAttribute('readonly');
                        input.classList.remove('bg-gray-50');
                        input.classList.add('bg-white');
                    }
                });
                profileSelects.forEach(select => {
                    select.removeAttribute('disabled');
                    select.classList.remove('bg-gray-50');
                    select.classList.add('bg-white');
                });
                profileFieldContainers.forEach(container => {
                    container.classList.remove('bg-gray-50');
                    container.classList.add('bg-white');
                });
            } else {
                window.location.reload();
            }
        }

        function cancelEdit() {
            window.location.reload();
        }

        function togglePasswordChange() {
            isPasswordChangeMode = !isPasswordChangeMode;
            const passwordSection = document.getElementById('password-change-section');
            const changeBtn = document.getElementById('change-password-btn');
            if (isPasswordChangeMode) {
                passwordSection.classList.remove('hidden');
                changeBtn.classList.add('hidden');
            } else {
                passwordSection.classList.add('hidden');
                changeBtn.classList.remove('hidden');
                document.getElementById('password-form').reset();
            }
        }

        function handleBusinessCategoryChange() {
            const categorySelect = document.getElementById('business_category');
            const customField = document.getElementById('custom-business-category-field');
            if (categorySelect.value === 'other') {
                customField.classList.remove('hidden');
                customField.classList.add('flex');
            } else {
                customField.classList.add('hidden');
                customField.classList.remove('flex');
                document.getElementById('custom_business_category').value = '';
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            handleBusinessCategoryChange();
        });
    </script>

    <style>
        .profile-input:read-only,
        .profile-select:disabled {
            cursor: default;
        }

        .profile-input,
        .profile-select {
            transition: all 0.2s ease;
        }

        .notification-popup {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 8px;
            z-index: 9999;
            opacity: 0;
            transform: translateY(-20px);
            animation: fadeInSlideDown 4s ease-in-out forwards;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            background-color: #ffffff;
            color: #1F2937;
            font-family: 'Lato', sans-serif;
            display: flex;
            align-items: center;
        }

        .notification-popup.success {
            border-left: 5px solid #F97316;
            /* Orange border for success */
        }

        .notification-popup.error {
            border-left: 5px solid #ef4444;
            /* Red border for error */
        }

        @keyframes fadeInSlideDown {
            0% {
                opacity: 0;
                transform: translateX(100%);
            }

            10% {
                opacity: 1;
                transform: translateX(0);
            }

            90% {
                opacity: 1;
                transform: translateX(0);
            }

            100% {
                opacity: 0;
                transform: translateX(100%);
            }
        }
    </style>
</body>

</html>