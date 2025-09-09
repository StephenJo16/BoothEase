<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FAQ - {{ config('app.name', 'BoothEase') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen">
    @include('components.navbar')

    <div class="relative pt-16 sm:pt-20 min-h-screen">
        @include('components.back-button')

        <!-- FAQ Content -->
        <div class="flex items-start justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl w-full">
                <div class="bg-white rounded-lg border border-gray-200 p-8 shadow-sm">
                    <div class="mb-8">
                        <h1 class="text-center text-4xl font-bold text-gray-900 mb-4">
                            Frequently Asked Questions
                        </h1>
                        <p class="text-center text-gray-600">
                            Find answers to common questions about BoothEase
                        </p>
                    </div>

                    <!-- FAQ Items -->
                    <div class="space-y-6">
                        <!-- FAQ Item 1 -->
                        <div class="border border-gray-200 rounded-lg focus-within:ring-2 focus-within:ring-[#ff7700] focus-within:ring-offset-2 transition-all duration-300">
                            <button class="faq-toggle w-full text-left p-6 focus:outline-none rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200" type="button">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg font-semibold text-gray-900">What is BoothEase?</h3>
                                    <svg class="faq-icon w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </button>
                            <div class="faq-content hidden px-6 pb-6">
                                <p class="text-gray-600">BoothEase is a comprehensive booth management platform that helps businesses streamline their booth operations, manage bookings, and enhance customer experiences at events and exhibitions.</p>
                            </div>
                        </div>

                        <!-- FAQ Item 2 -->
                        <div class="border border-gray-200 rounded-lg focus-within:ring-2 focus-within:ring-[#ff7700] focus-within:ring-offset-2 transition-all duration-300">
                            <button class="faq-toggle w-full text-left p-6 focus:outline-none rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200" type="button">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg font-semibold text-gray-900">How do I create an account?</h3>
                                    <svg class="faq-icon w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </button>
                            <div class="faq-content hidden px-6 pb-6">
                                <p class="text-gray-600">To create an account, click on the "Sign Up" button in the navigation bar or go to the signup page. Fill in your details including your mobile number, password, and other required information. You'll receive a verification code to confirm your account.</p>
                            </div>
                        </div>

                        <!-- FAQ Item 3 -->
                        <div class="border border-gray-200 rounded-lg focus-within:ring-2 focus-within:ring-[#ff7700] focus-within:ring-offset-2 transition-all duration-300">
                            <button class="faq-toggle w-full text-left p-6 focus:outline-none rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200" type="button">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg font-semibold text-gray-900">How do I reset my password?</h3>
                                    <svg class="faq-icon w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </button>
                            <div class="faq-content hidden px-6 pb-6">
                                <p class="text-gray-600">On the sign-in page, click on "Forgot Password?" link. Enter your registered mobile number and follow the instructions sent to your device to reset your password.</p>
                            </div>
                        </div>

                        <!-- FAQ Item 4 -->
                        <!-- <div class="border border-gray-200 rounded-lg focus-within:ring-2 focus-within:ring-[#ff7700] focus-within:ring-offset-2">
                            <button class="faq-toggle w-full text-left p-6 focus:outline-none rounded-lg" type="button">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg font-semibold text-gray-900">What payment methods do you accept?</h3>
                                    <svg class="faq-icon w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </button>
                            <div class="faq-content hidden px-6 pb-6">
                                <p class="text-gray-600">We accept various payment methods including credit cards (Visa, MasterCard, American Express), debit cards, bank transfers, and popular digital wallets like GoPay, OVO, and DANA.</p>
                            </div>
                        </div> -->

                        <!-- FAQ Item 5 -->
                        <div class="border border-gray-200 rounded-lg focus-within:ring-2 focus-within:ring-[#ff7700] focus-within:ring-offset-2 transition-all duration-300">
                            <button class="faq-toggle w-full text-left p-6 focus:outline-none rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200" type="button">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg font-semibold text-gray-900">Can I cancel or modify my booking?</h3>
                                    <svg class="faq-icon w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </button>
                            <div class="faq-content hidden px-6 pb-6">
                                <p class="text-gray-600">Yes, you can cancel or modify your booking up to 24 hours before your scheduled time. Please note that cancellation fees may apply depending on the timing and booth provider's policy.</p>
                            </div>
                        </div>


                        <!-- FAQ Item 6 -->
                        <div class="border border-gray-200 rounded-lg focus-within:ring-2 focus-within:ring-[#ff7700] focus-within:ring-offset-2 transition-all duration-300">
                            <button class="faq-toggle w-full text-left p-6 focus:outline-none rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200" type="button">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg font-semibold text-gray-900">Is my personal information secure?</h3>
                                    <svg class="faq-icon w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </button>
                            <div class="faq-content hidden px-6 pb-6">
                                <p class="text-gray-600">Yes, we take data security very seriously. All personal information is encrypted and stored securely. We comply with international data protection standards and never share your personal information with third parties without your consent.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- FAQ JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const faqToggles = document.querySelectorAll('.faq-toggle');

            faqToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const content = this.nextElementSibling;
                    const icon = this.querySelector('.faq-icon');

                    if (content.classList.contains('hidden')) {
                        content.classList.remove('hidden');
                        content.style.maxHeight = '0px';
                        content.style.overflow = 'hidden';
                        content.style.transition = 'max-height 0.3s ease-out';

                        requestAnimationFrame(() => {
                            content.style.maxHeight = content.scrollHeight + 'px';
                        });

                        icon.style.transform = 'rotate(180deg)';
                    } else {
                        content.style.maxHeight = '0px';
                        content.addEventListener('transitionend', function handler() {
                            content.classList.add('hidden');
                            content.style.maxHeight = '';
                            content.style.overflow = '';
                            content.style.transition = '';
                            content.removeEventListener('transitionend', handler);
                        });
                        icon.style.transform = 'rotate(0deg)';
                    }
                });
            });
        });
    </script>
    <!-- Footer -->
    @include('components.footer')
</body>

</html>