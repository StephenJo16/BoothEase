@props(['label' => 'Filter', 'type' => 'category', 'categories' => [], 'selectedCategories' => [], 'selectedStatuses' => [], 'provinces' => [], 'cities' => [], 'selectedProvinceId' => '', 'selectedCityId' => ''])

<div class="relative inline-block">
    <button
        type="button"
        class="filter-toggle inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] transition-all duration-200 cursor-pointer">
        <i class="fas fa-filter mr-2 text-gray-500"></i>
        <span>{{ $label }}</span>
        <i class="fas fa-chevron-down ml-2 text-gray-400 transform transition-transform duration-200"></i>
    </button>

    <!-- Dropdown Menu -->
    <div class="filter-dropdown hidden absolute right-0 mt-2 w-[480px] bg-white rounded-lg shadow-lg border border-gray-200 z-50">
        <div class="py-2">
            @if($type === 'category' || $type === 'combined')
            <!-- Category Filter Section -->
            <div class="px-4 py-2 text-sm font-semibold text-gray-900 border-b border-gray-200">
                Filter by Category
            </div>
            <div class="grid grid-cols-2 gap-1 px-2 py-2">
                @foreach($categories as $category)
                <label class="flex items-center px-2 py-2 hover:bg-gray-50 cursor-pointer rounded">
                    <input
                        type="checkbox"
                        name="categories[]"
                        value="{{ $category->id }}"
                        {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}
                        class="filter-checkbox mr-2 accent-[#ff7700] focus:ring-[#ff7700] border-gray-300 rounded flex-shrink-0">
                    <span class="text-sm text-gray-700">{{ $category->name }}</span>
                </label>
                @endforeach
            </div>

            @if(!empty($provinces) || !empty($cities))
            <!-- Location Filter Section -->
            <div class="px-4 py-2 mt-2 text-sm font-semibold text-gray-900 border-t border-gray-200">
                Filter by Location
            </div>
            <div class="grid grid-cols-2 gap-3 px-4 py-2">
                @if(!empty($provinces))
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Province</label>
                    <select
                        name="province_id"
                        class="location-select w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700]">
                        <option value="">All Provinces</option>
                        @foreach($provinces as $province)
                        <option value="{{ $province->id }}" {{ $selectedProvinceId == $province->id ? 'selected' : '' }}>
                            {{ $province->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @endif

                @if(!empty($cities))
                <div>
                    <label class="block text-xs text-gray-600 mb-1">City</label>
                    <select
                        name="city_id"
                        class="location-select w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700]">
                        <option value="">All Cities</option>
                        @foreach($cities as $city)
                        <option value="{{ $city->id }}" {{ $selectedCityId == $city->id ? 'selected' : '' }}>
                            {{ $city->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
            @endif
            @elseif($type === 'status')
            <!-- Status Filter Section -->
            <div class="px-4 py-2 text-sm font-semibold text-gray-900 border-b border-gray-200">
                Filter by Status
            </div>
            @php
            $statusOptions = [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'ongoing' => 'Ongoing',
            'completed' => 'Completed',
            'paid' => 'Paid',
            'rejected' => 'Rejected',
            'cancelled' => 'Cancelled',
            ];
            @endphp
            @foreach($statusOptions as $statusValue => $statusLabel)
            <label class="flex items-center px-4 py-2 hover:bg-gray-50 cursor-pointer">
                <input
                    type="checkbox"
                    name="statuses[]"
                    value="{{ $statusValue }}"
                    {{ in_array($statusValue, $selectedStatuses) ? 'checked' : '' }}
                    class="filter-checkbox mr-3 accent-[#ff7700] focus:ring-[#ff7700] border-gray-300 rounded">
                <span class="text-sm text-gray-700">{{ $statusLabel }}</span>
            </label>
            @endforeach
            @elseif($type === 'event-status' || $type === 'combined')
            <!-- Event Status Filter Section -->
            <div class="px-4 py-2 text-sm font-semibold text-gray-900 border-b border-gray-200">
                Filter by Status
            </div>
            @php
            $eventStatusOptions = [
            'draft' => 'Draft',
            'finalized' => 'Finalized',
            'published' => 'Published',
            'ongoing' => 'Ongoing',
            'completed' => 'Completed',
            ];
            @endphp
            @foreach($eventStatusOptions as $statusValue => $statusLabel)
            <label class="flex items-center px-4 py-2 hover:bg-gray-50 cursor-pointer">
                <input
                    type="checkbox"
                    name="statuses[]"
                    value="{{ $statusValue }}"
                    {{ in_array($statusValue, $selectedStatuses) ? 'checked' : '' }}
                    class="filter-checkbox mr-3 accent-[#ff7700] focus:ring-[#ff7700] border-gray-300 rounded">
                <span class="text-sm text-gray-700">{{ $statusLabel }}</span>
            </label>
            @endforeach
            @endif

            <!-- Action Buttons -->
            <div class="px-4 py-2 mt-2 border-t border-gray-200 flex space-x-2">
                <button
                    type="button"
                    onclick="document.getElementById('filter-form').submit()"
                    class="flex-1 bg-[#ff7700] hover:bg-[#e66600] text-white text-sm font-medium py-2 px-4 rounded transition-colors duration-200 cursor-pointer">
                    Apply
                </button>
                <button
                    type="button"
                    onclick="clearFilters()"
                    class="clear-btn flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium py-2 px-4 rounded transition-colors duration-200 cursor-pointer">
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

        document.addEventListener('click', function(e) {
            if (!filterToggle.contains(e.target) && !filterDropdown.contains(e.target)) {
                filterDropdown.classList.add('hidden');
                chevron.classList.remove('rotate-180');
            }
        });
    });

    function clearFilters() {
        // Clear all checkboxes
        document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });

        // Clear all location selects
        document.querySelectorAll('.location-select').forEach(select => {
            select.value = '';
        });

        // Clear search input
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.value = '';
        }

        // Submit the form to reset filters
        document.getElementById('filter-form').submit();
    }
</script>