<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In - {{ config('app.name', 'BoothEase') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

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
                    <form class="space-y-6" action="{{ route('login') }}" method="POST">
                        @csrf
                        <div>
                            <div class="flex border @error('mobile_number') border-red-500 @else border-gray-300 @enderror rounded-lg focus-within:ring-2 focus-within:ring-[#ff7700] focus-within:border-[#ff7700] transition-all duration-200 h-12">
                                <div class="relative">
                                    <select name="country_code" class="appearance-none bg-white border-0 rounded-l-lg px-3 py-3 pr-8 text-gray-700 focus:outline-none focus:ring-0 h-full">
                                        <option value="+62" {{ old('country_code', '+62') == '+62' ? 'selected' : '' }}>🇮🇩 +62</option>
                                        <option value="+1" {{ old('country_code') == '+1' ? 'selected' : '' }}>🇺🇸 +1</option>
                                        <option value="+44" {{ old('country_code') == '+44' ? 'selected' : '' }}>🇬🇧 +44</option>
                                        <option value="+81" {{ old('country_code') == '+81' ? 'selected' : '' }}>🇯🇵 +81</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                        </svg>
                                    </div>
                                </div>
                                <input
                                    type="tel"
                                    name="mobile_number"
                                    id="mobile_number"
                                    class="flex-1 block w-full border-0 border-l border-gray-300 rounded-r-lg px-3 py-3 placeholder-gray-400 focus:outline-none focus:ring-0 h-full"
                                    placeholder="Mobile Number"
                                    value="{{ old('mobile_number') }}"
                                    required>
                            </div>
                            @error('mobile_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="block w-full border @error('password') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] h-12"
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