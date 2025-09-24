<nav class="bg-white shadow-sm border-b border-gray-200 relative z-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <a href="/" class="flex items-center space-x-2">
                    <img src="{{ asset('images/boothease-logo-cropped.png') }}" alt="BoothEase" class="h-10 w-auto">
                </a>
            </div>

            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('events') }}" class="text-gray-700 hover:text-[#ff7700] font-medium transition-colors">Events</a>
                <a href="#" class="text-gray-700 hover:text-[#ff7700] font-medium transition-colors">How it Works</a>
                <a href="{{ route('faq') }}" class="text-gray-700 hover:text-[#ff7700] font-medium transition-colors">FAQ</a>

                @auth
                    <div class="flex items-center space-x-4">
                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-[#ff7700] font-medium transition-colors">
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center space-x-2">
                         <a href="{{ route('login') }}" class="text-gray-700 hover:text-[#ff7700] font-medium transition-colors px-4 py-2 rounded-md">
                            Sign In
                        </a>
                        <a href="{{ route('signup') }}" class="bg-[#ff7700] hover:bg-[#e66600] text-white font-semibold px-4 py-2 rounded-md transition-colors duration-200">
                            Sign Up
                        </a>
                    </div>
                @endguest
            </div>

            <div class="md:hidden flex items-center">
                <button type="button" id="mobile-menu-button" class="text-gray-700 hover:text-[#ff7700] focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div id="mobile-menu" class="hidden md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 bg-white border-t border-gray-200">
            <a href="{{ route('events') }}" class="block px-3 py-2 text-gray-700 hover:text-[#ff7700] font-medium">Events</a>
            <a href="#" class="block px-3 py-2 text-gray-700 hover:text-[#ff7700] font-medium">How it Works</a>
            <a href="{{ route('faq') }}" class="block px-3 py-2 text-gray-700 hover:text-[#ff7700] font-medium">FAQ</a>
            <div class="border-t border-gray-200 my-2"></div>
            @guest
                <a href="{{ route('login') }}" class="block px-3 py-2 text-gray-700 hover:text-[#ff7700] font-medium">Sign In</a>
                <a href="{{ route('signup') }}" class="block px-3 py-2 text-gray-700 hover:text-[#ff7700] font-medium">Sign Up</a>
            @else
                <div class="px-3 py-2 text-gray-500 text-sm">
                    {{ Auth::user()->display_name }}
                </div>
                 <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left block px-3 py-2 text-gray-700 hover:text-[#ff7700] font-medium">
                        Logout
                    </button>
                </form>
            @endguest
        </div>
    </div>
</nav>