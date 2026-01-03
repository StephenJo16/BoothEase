<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Email</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">
    @include('components.navbar')

    <div class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <div class="bg-white rounded-lg border border-gray-200 p-8 shadow-sm text-center">
                <div class="mb-6">
                    <i class="fa-regular fa-envelope text-6xl text-[#ff7700]"></i>
                </div>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">
                    Verify Your Email Address
                </h2>

                <p class="text-gray-600 mb-6">
                    Verify your email Body: A verification link has been sent to your registered email address. Please follow the instructions in the email to complete your registration and access your account.
                </p>
            </div>
        </div>
    </div>
</body>

</html>