<nav class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="/" class="flex items-center space-x-2">
                    <img src="{{ asset('images/boothease-logo-cropped.png') }}" alt="BoothEase" class="h-10 w-auto">
                </a>
            </div>

            <!-- Navigation Links and User Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="#" class="text-gray-700 hover:text-[#ff7700] font-medium transition-colors">Event List</a>
                <a href="#" class="text-gray-700 hover:text-[#ff7700] font-medium transition-colors">How it Works</a>
                <a href="#" class="text-gray-700 hover:text-[#ff7700] font-medium transition-colors">Pricing</a>
                <a href="#" class="text-gray-700 hover:text-[#ff7700] font-medium transition-colors">FAQ</a>

                <!-- User Icon/Profile -->
                <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center hover:cursor-pointer">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24 ">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button type="button" class="mobile-menu-button text-gray-700 hover:text-[#ff7700] focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div class="mobile-menu hidden md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 bg-white border-t border-gray-200">
            <a href="#" class="block px-3 py-2 text-gray-700 hover:text-[#ff7700] font-medium">Event List</a>
            <a href="#" class="block px-3 py-2 text-gray-700 hover:text-[#ff7700] font-medium">How it Works</a>
            <a href="#" class="block px-3 py-2 text-gray-700 hover:text-[#ff7700] font-medium">Pricing</a>
            <a href="#" class="block px-3 py-2 text-gray-700 hover:text-[#ff7700] font-medium">FAQ</a>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.querySelector('.mobile-menu-button');
        const mobileMenu = document.querySelector('.mobile-menu');

        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    });
</script>