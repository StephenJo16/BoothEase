@php
$currentPerPage = isset($perPage) && is_numeric($perPage) ? (int) $perPage : (request()->integer('perPage') ?: 10);
$perPageOptions = isset($perPageOptions) && is_array($perPageOptions) ? $perPageOptions : [10, 20, 50, 100];
@endphp

<div class="mt-12 flex flex-col md:flex-row items-center justify-end gap-4">
    <!-- Page size selector -->
    <div class="flex items-center gap-2 text-sm text-gray-700">
        <label for="perPage" class="whitespace-nowrap">Show</label>
        <select id="perPage" class="border border-gray-300 rounded-md px-2 py-1 focus:outline-none focus:ring-2 focus:ring-[#ff7700]"
            onchange="(function(sel){
                const url = new URL(window.location.href);
                url.searchParams.set('perPage', sel.value);
                url.searchParams.set('page', '1');
                window.location.href = url.toString();
            })(this)">
            @foreach($perPageOptions as $opt)
            <option value="{{ $opt }}" {{ (int)$opt === (int)$currentPerPage ? 'selected' : '' }}>{{ $opt }}</option>
            @endforeach
        </select>
        <span class="text-gray-500">per page</span>
    </div>

    <nav class="relative z-0 inline-flex rounded-lg shadow-md bg-white overflow-hidden" aria-label="Pagination">
        <a href="#" class="hover:cursor-pointer relative inline-flex items-center px-3 py-2 border border-gray-200 text-sm font-medium text-gray-600 hover:bg-[#ff7700] hover:text-white transition-colors duration-200">
            <i class="fa-solid fa-angle-left"></i>
        </a>
        <a href="#" aria-current="page" class="hover:cursor-pointer z-10 bg-[#ff7700] border-[#ff7700] text-white relative inline-flex items-center px-4 py-2 border text-sm font-medium shadow-sm"> 1 </a>
        <a href="#" class="hover:cursor-pointer bg-white border-gray-200 text-gray-600 hover:bg-[#ff7700] hover:text-white relative inline-flex items-center px-4 py-2 border text-sm font-medium transition-colors duration-200"> 2 </a>
        @if(isset($showEllipsis) && $showEllipsis)
        <a href="#" class="hover:cursor-pointer bg-white border-gray-200 text-gray-600 hover:bg-[#ff7700] hover:text-white hidden md:inline-flex relative items-center px-4 py-2 border text-sm font-medium transition-colors duration-200"> 3 </a>
        <span class="relative inline-flex items-center px-4 py-2 border border-gray-200 bg-gray-50 text-sm font-medium text-gray-500"> ... </span>
        <a href="#" class="hover:cursor-pointer bg-white border-gray-200 text-gray-600 hover:bg-[#ff7700] hover:text-white hidden md:inline-flex relative items-center px-4 py-2 border text-sm font-medium transition-colors duration-200"> 8 </a>
        <a href="#" class="hover:cursor-pointer bg-white border-gray-200 text-gray-600 hover:bg-[#ff7700] hover:text-white relative inline-flex items-center px-4 py-2 border text-sm font-medium transition-colors duration-200"> 9 </a>
        <a href="#" class="hover:cursor-pointer bg-white border-gray-200 text-gray-600 hover:bg-[#ff7700] hover:text-white relative inline-flex items-center px-4 py-2 border text-sm font-medium transition-colors duration-200"> 10 </a>
        @endif
        <a href="#" class="hover:cursor-pointer relative inline-flex items-center px-3 py-2 border border-gray-200 text-sm font-medium text-gray-600 hover:bg-[#ff7700] hover:text-white transition-colors duration-200">
            <i class="fa-solid fa-angle-right"></i>
        </a>
    </nav>
</div>