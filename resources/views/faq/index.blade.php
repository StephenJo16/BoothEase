<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FAQ - Boothease</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .faq-answer {
            display: grid;
            grid-template-rows: 0fr;
            transition: grid-template-rows 0.3s ease-out, padding 0.3s ease-out;
        }

        .faq-answer.open {
            grid-template-rows: 1fr;
        }

        .faq-answer-inner {
            overflow: hidden;
        }

        .faq-icon {
            transition: transform 0.3s ease-out;
        }

        .faq-item.active .faq-icon {
            transform: rotate(180deg);
        }

        .faq-item {
            transition: all 0.2s ease-out;
        }

        .faq-item.active {
            background-color: #fff7ed;
            border-color: #ff7700;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    @include('components.navbar')


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
                <div class="space-y-4">
                    <!-- FAQ Item 1 -->
                    <div class="faq-item border border-gray-200 rounded-lg overflow-hidden">
                        <button class="faq-toggle w-full text-left p-6 focus:outline-none cursor-pointer" type="button">
                            <div class="flex justify-between items-center gap-4">
                                <h3 class="text-lg font-semibold text-gray-900">What is BoothEase?</h3>
                                <i class="faq-icon fa-solid fa-chevron-down text-[#ff7700] flex-shrink-0"></i>
                            </div>
                        </button>
                        <div class="faq-answer">
                            <div class="faq-answer-inner">
                                <div class="px-6 pb-6">
                                    <p class="text-gray-600">BoothEase is a comprehensive booth management platform that helps businesses streamline their booth operations, manage bookings, and enhance customer experiences at events and exhibitions.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div class="faq-item border border-gray-200 rounded-lg overflow-hidden">
                        <button class="faq-toggle w-full text-left p-6 focus:outline-none cursor-pointer" type="button">
                            <div class="flex justify-between items-center gap-4">
                                <h3 class="text-lg font-semibold text-gray-900">How do I create an account?</h3>
                                <i class="faq-icon fa-solid fa-chevron-down text-[#ff7700] flex-shrink-0"></i>
                            </div>
                        </button>
                        <div class="faq-answer">
                            <div class="faq-answer-inner">
                                <div class="px-6 pb-6">
                                    <p class="text-gray-600">To create an account, click on the "Sign Up" button in the navigation bar or go to the signup page. Fill in your details including your mobile number, password, and other required information. You can also Sign Up with Google by clicking the "Sign Up with Google" button.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 3 -->
                    <!-- <div class="faq-item border border-gray-200 rounded-lg overflow-hidden">
                        <button class="faq-toggle w-full text-left p-6 focus:outline-none cursor-pointer" type="button">
                            <div class="flex justify-between items-center gap-4">
                                <h3 class="text-lg font-semibold text-gray-900">How do I reset my password?</h3>
                                <i class="faq-icon fa-solid fa-chevron-down text-[#ff7700] flex-shrink-0"></i>
                            </div>
                        </button>
                        <div class="faq-answer">
                            <div class="faq-answer-inner">
                                <div class="px-6 pb-6">
                                    <p class="text-gray-600">On the sign-in page, click on "Forgot Password?" link. Enter your registered mobile number and follow the instructions sent to your device to reset your password.</p>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <!-- FAQ Item 4 -->
                    <!-- <div class="faq-item border border-gray-200 rounded-lg overflow-hidden">
                            <button class="faq-toggle w-full text-left p-6 focus:outline-none cursor-pointer" type="button">
                                <div class="flex justify-between items-center gap-4">
                                    <h3 class="text-lg font-semibold text-gray-900">What payment methods do you accept?</h3>
                                    <i class="faq-icon fa-solid fa-chevron-down text-[#ff7700] flex-shrink-0"></i>
                                </div>
                            </button>
                            <div class="faq-answer">
                                <div class="faq-answer-inner">
                                    <div class="px-6 pb-6">
                                        <p class="text-gray-600">We accept various payment methods including credit cards (Visa, MasterCard, American Express), debit cards, bank transfers, and popular digital wallets like GoPay, OVO, and DANA.</p>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                    <!-- FAQ Item 5 -->
                    <div class="faq-item border border-gray-200 rounded-lg overflow-hidden">
                        <button class="faq-toggle w-full text-left p-6 focus:outline-none cursor-pointer" type="button">
                            <div class="flex justify-between items-center gap-4">
                                <h3 class="text-lg font-semibold text-gray-900">Can I cancel or modify my booking?</h3>
                                <i class="faq-icon fa-solid fa-chevron-down text-[#ff7700] flex-shrink-0"></i>
                            </div>
                        </button>
                        <div class="faq-answer">
                            <div class="faq-answer-inner">
                                <div class="px-6 pb-6">
                                    <p class="text-gray-600">Yes, you can request to cancel your booking if the Event Organizer has allowed refund requests for the event.</p>
                                </div>
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
            const faqItems = document.querySelectorAll('.faq-item');

            faqItems.forEach(item => {
                const toggle = item.querySelector('.faq-toggle');
                const answer = item.querySelector('.faq-answer');

                toggle.addEventListener('click', function() {
                    const isActive = item.classList.contains('active');

                    // Close all other items
                    faqItems.forEach(otherItem => {
                        if (otherItem !== item) {
                            otherItem.classList.remove('active');
                            otherItem.querySelector('.faq-answer').classList.remove('open');
                        }
                    });

                    // Toggle current item
                    if (isActive) {
                        item.classList.remove('active');
                        answer.classList.remove('open');
                    } else {
                        item.classList.add('active');
                        answer.classList.add('open');
                    }
                });
            });
        });
    </script>
    <!-- Footer -->
    @include('components.footer')
</body>

</html>