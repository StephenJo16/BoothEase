<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In - BoothEase</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 overflow-hidden">
    @include('components.navbar')

    <div class="pt-2 lg:pt-0">
        <div class="flex items-start lg:items-center justify-center h-[calc(100vh-6.5rem)] lg:h-[calc(100vh-4rem)] py-2 lg:py-0 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full">
                <div class="bg-white rounded-lg border border-gray-200 p-8 shadow-sm">
                    <div class="mb-8">
                        <h2 class="text-center text-4xl font-bold text-gray-900">
                            Sign In
                        </h2>
                    </div>

                    <!-- Google Sign In Button -->
                    <div class="mb-6">
                        <a href="{{ route('google.redirect') }}"
                            class="w-full flex gap-x-4 items-center justify-center px-4 py-3 border border-gray-200 rounded-lg shadow-sm bg-white text-gray-700 hover:bg-gray-50 transition-colors duration-200 h-12">
                            <i class="fa-brands fa-google"></i>
                            <span class="font-medium">Log In with Google</span>
                        </a>
                    </div>

                    <!-- Divider -->
                    <div class="relative mb-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">Or continue with email</span>
                        </div>
                    </div>

                    <form class="space-y-6" action="{{ route('login') }}" method="POST">
                        @csrf
                        <div>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="block w-full border @error('email') @else border-gray-300 @enderror rounded-lg px-3 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] h-12"
                                placeholder="Email Address"
                                value="{{ old('email') }}"
                                required>
                            @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="block w-full border @error('password') @else border-gray-300 @enderror rounded-lg px-3 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] h-12"
                                placeholder="Password"
                                required>
                            @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <button
                                type="submit"
                                class="w-full bg-[#ff7700] hover:bg-[#e66600] text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 text-lg h-12">
                                Sign In
                            </button>
                        </div>

                        <div class="text-center">
                            <span class="text-gray-600">Don't have an account?</span>
                            <a href="{{ route('signup') }}" class="text-[#ff7700] hover:text-[#e66600] font-medium ml-1 transition-colors">
                                Sign Up
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>