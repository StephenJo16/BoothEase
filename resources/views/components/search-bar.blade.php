<div class="relative flex items-center">
    <input
        type="text"
        name="search"
        value="{{ $value ?? '' }}"
        placeholder="{{ $placeholder ?? 'Search events...' }}"
        class="block w-full pl-4 pr-12 py-2.5 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] transition-all duration-200">
    <button type="submit" class="hover:cursor-pointer absolute inset-y-0 right-0 pr-3 flex items-center hover:text-[#ff7700] transition-colors duration-200">
        <i class="fas fa-search text-gray-400 hover:text-[#ff7700]"></i>
    </button>
</div>