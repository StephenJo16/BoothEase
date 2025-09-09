<div class="mt-12 flex justify-center">
    <nav class="relative z-0 inline-flex rounded-lg shadow-md bg-white overflow-hidden" aria-label="Pagination">
        <a href="#" class="hover:cursor-pointer relative inline-flex items-center px-3 py-2 border border-gray-200 text-sm font-medium text-gray-600 hover:bg-[#ff7700] hover:text-white transition-colors duration-200">
            <span class="sr-only">Previous</span>
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
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
            <span class="sr-only">Next</span>
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
        </a>
    </nav>
</div>