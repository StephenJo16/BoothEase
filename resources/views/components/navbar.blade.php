<nav class="bg-white shadow-sm border-b border-gray-200 relative z-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <a href="/" class="flex items-center space-x-2">
                    <img src="{{ asset('images/boothease-logo-cropped.webp') }}" alt="BoothEase" class="h-10 w-auto">
                </a>
            </div>

            @php
            $isOrganizer = auth()->check() && auth()->user()->role_id === 3;
            $eventLinkHref = $isOrganizer ? route('my-events.index') : route('events');
            $eventLinkLabel = $isOrganizer ? 'My Events' : 'Events';
            $isTenant = auth()->check() && auth()->user()->role_id === 2;
            @endphp
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ $eventLinkHref }}" class="text-gray-700 hover:text-[#ff7700] font-medium transition-colors">{{ $eventLinkLabel }}</a>
                @if($isTenant)
                <a href="{{ route('my-bookings') }}" class="text-gray-700 hover:text-[#ff7700] font-medium transition-colors">My Bookings</a>
                @endif
                <a href="{{ route('faq') }}" class="text-gray-700 hover:text-[#ff7700] font-medium transition-colors">FAQ</a>

                @auth
                <div class="relative">
                    <button type="button" id="profile-menu-button" class="flex items-center space-x-2 hover:cursor-pointer text-gray-700 focus:outline-none transition-colors">
                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center hover:bg-gray-300 transition-colors">
                            <i class="fa-regular fa-user"></i>
                        </div>
                    </button>

                    <div id="profile-dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 border border-gray-200 hidden">
                        <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:cursor-pointer hover:bg-gray-100 hover:text-[#ff7700] transition-colors">
                            <div class="flex items-center">
                                <i class="fa-regular fa-user mr-4"></i>
                                View Profile
                            </div>
                        </a>
                        <div class="border-t border-gray-100"></div>
                        <a href="{{ route('logout.get') }}" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:cursor-pointer hover:bg-gray-100 hover:text-[#ff7700] transition-colors">
                            <div class="flex items-center">
                                <i class="fa-solid fa-arrow-right-from-bracket mr-4"></i>
                                Logout
                            </div>
                        </a>
                    </div>
                </div>
                @else
                <div class="flex items-center space-x-2">
                    <a href="{{ route('login') }}" class="bg-[#ff7700] hover:bg-[#e66600] hover:cursor-pointer text-white font-semibold px-4 py-2 rounded-md transition-colors duration-200">
                        Login
                    </a>
                </div>
                @endguest
            </div>

            <div class="md:hidden flex items-center">
                <button type="button" id="mobile-menu-button" class="text-gray-700 hover:text-[#ff7700] focus:outline-none">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
        </div>
    </div>

    <div id="mobile-menu" class="hidden md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 bg-white border-t border-gray-200">
            <a href="{{ $eventLinkHref }}" class="block px-3 py-2 text-gray-700 hover:text-[#ff7700] font-medium">{{ $eventLinkLabel }}</a>
            @if($isTenant)
            <a href="{{ route('my-bookings') }}" class="block px-3 py-2 text-gray-700 hover:text-[#ff7700] font-medium">My Bookings</a>
            @endif
            <a href="{{ route('faq') }}" class="block px-3 py-2 text-gray-700 hover:text-[#ff7700] font-medium">FAQ</a>
            <div class="border-t border-gray-200 my-2"></div>
            @guest
            <a href="{{ route('login') }}" class="block px-3 py-2 text-gray-700 hover:text-[#ff7700] font-medium">Sign In</a>
            <a href="{{ route('signup') }}" class="block px-3 py-2 text-gray-700 hover:text-[#ff7700] font-medium">Sign Up</a>
            @else
            <div class="px-3 py-2 text-gray-500 text-sm">
                {{ Auth::user()->display_name }}
            </div>
            <a href="{{ route('profile') }}" class="block px-3 py-2 text-gray-700 hover:text-[#ff7700] font-medium">View Profile</a>
            <a href="{{ route('logout.get') }}" class="block px-3 py-2 text-gray-700 hover:text-[#ff7700] font-medium">
                Logout
            </a>
            @endguest
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Profile dropdown toggle
            const profileButton = document.getElementById('profile-menu-button');
            const profileDropdown = document.getElementById('profile-dropdown');

            if (profileButton && profileDropdown) {
                profileButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    profileDropdown.classList.toggle('hidden');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!profileButton.contains(e.target) && !profileDropdown.contains(e.target)) {
                        profileDropdown.classList.add('hidden');
                    }
                });
            }

            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        });
    </script>
</nav>