<div class="relative inline-block">
    <button
        type="button"
        class="filter-toggle inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] transition-all duration-200 cursor-pointer">
        <i class="fas fa-filter mr-2 text-gray-500"></i>
        <span>{{ $label ?? 'Filter' }}</span>
        <i class="fas fa-chevron-down ml-2 text-gray-400 transform transition-transform duration-200"></i>
    </button>

    <!-- Dropdown Menu -->
    <div class="filter-dropdown hidden absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
        <div class="py-2">
            <div class="px-4 py-2 text-sm font-semibold text-gray-900 border-b border-gray-200">
                Filter by Category
            </div>
            <label class="flex items-center px-4 py-2 hover:bg-gray-50 cursor-pointer">
                <input type="checkbox" class="mr-3 accent-[#ff7700] focus:ring-[#ff7700] border-gray-300 rounded">
                <span class="text-sm text-gray-700">Technology</span>
            </label>
            <label class="flex items-center px-4 py-2 hover:bg-gray-50 cursor-pointer">
                <input type="checkbox" class="mr-3 accent-[#ff7700] focus:ring-[#ff7700] border-gray-300 rounded">
                <span class="text-sm text-gray-700">Food & Beverage</span>
            </label>
            <label class="flex items-center px-4 py-2 hover:bg-gray-50 cursor-pointer">
                <input type="checkbox" class="mr-3 accent-[#ff7700] focus:ring-[#ff7700] border-gray-300 rounded">
                <span class="text-sm text-gray-700">Fashion</span>
            </label>
            <label class="flex items-center px-4 py-2 hover:bg-gray-50 cursor-pointer">
                <input type="checkbox" class="mr-3 accent-[#ff7700] focus:ring-[#ff7700] border-gray-300 rounded">
                <span class="text-sm text-gray-700">Business</span>
            </label>

            <div class="px-4 py-2 mt-2 text-sm font-semibold text-gray-900 border-t border-gray-200">
                Filter by Price
            </div>
            <div class="px-4 py-2 space-y-3">
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Minimum Price (Rp)</label>
                    <input type="number" placeholder="0" class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700]">
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Maximum Price (Rp)</label>
                    <input type="number" placeholder="10000000" class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700]">
                </div>
            </div>

            <div class="px-4 py-2 mt-2 border-t border-gray-200 flex space-x-2">
                <button class="flex-1 bg-[#ff7700] hover:bg-[#e66600] text-white text-sm font-medium py-2 px-4 rounded transition-colors duration-200 cursor-pointer">
                    Apply
                </button>
                <button class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium py-2 px-4 rounded transition-colors duration-200 cursor-pointer">
                    Clear
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterToggle = document.querySelector('.filter-toggle');
        const filterDropdown = document.querySelector('.filter-dropdown');
        const chevron = filterToggle.querySelector('.fa-chevron-down');

        filterToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            filterDropdown.classList.toggle('hidden');
            chevron.classList.toggle('rotate-180');
        });

        clearBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            numberInputs.forEach(input => {
                input.value = '';
            });
        });

        document.addEventListener('click', function(e) {
            if (!filterToggle.contains(e.target) && !filterDropdown.contains(e.target)) {
                filterDropdown.classList.add('hidden');
                chevron.classList.remove('rotate-180');
            }
        });
    });
</script>