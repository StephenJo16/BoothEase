{{--
    Universal Pagination Component
    
    Usage Examples:
    
    1. Basic usage with default options:
       <x-pagination :paginator="$items" />
    
    2. Custom per page options:
       <x-pagination :paginator="$items" :perPageOptions="[5, 15, 30]" />
    
    3. Hide per page selector:
       <x-pagination :paginator="$items" :showPerPageSelector="false" />
    
    4. Hide info text:
       <x-pagination :paginator="$items" :showInfo="false" />
    
    5. All options combined:
       <x-pagination 
           :paginator="$items" 
           :perPageOptions="[20, 40, 60]"
           :showPerPageSelector="true"
           :showInfo="true" 
       />
    
    Required Props:
    - paginator: Laravel paginator instance (from $query->paginate())
    
    Optional Props:
    - perPageOptions: array of integers (default: [10, 25, 50, 100])
    - showPerPageSelector: boolean (default: true)
    - showInfo: boolean (default: true)
--}}

@props([
'paginator', // Required: Laravel paginator instance
'perPageOptions' => [10, 25, 50, 100], // Optional: per page options
'showPerPageSelector' => true, // Optional: show/hide per page selector
'showInfo' => true, // Optional: show/hide "Showing X to Y of Z entries"
])

<div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Showing entries info -->
        @if($showInfo)
        <div class="text-sm text-gray-700">
            Showing {{ $paginator->firstItem() ?? 0 }} to {{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }} entries
        </div>
        @endif

        <div class="flex items-center gap-4">
            <!-- Per page selector -->
            @if($showPerPageSelector)
            <div class="flex items-center gap-2 text-sm text-gray-700">
                <label for="perPage" class="whitespace-nowrap">Show</label>
                <select id="perPage"
                    class="border border-gray-300 rounded-md px-2 py-1 focus:outline-none focus:ring-2 focus:ring-[#ff7700]"
                    onchange="window.location.href = updateQueryParam('perPage', this.value)">
                    @foreach($perPageOptions as $option)
                    <option value="{{ $option }}" {{ request('perPage', 10) == $option ? 'selected' : '' }}>
                        {{ $option }}
                    </option>
                    @endforeach
                </select>
                <span class="text-gray-500">per page</span>
            </div>
            @endif

            <!-- Pagination -->
            @if ($paginator->hasPages())
            <nav class="relative z-0 inline-flex rounded-lg shadow-md bg-white overflow-hidden" aria-label="Pagination">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-3 py-2 border border-gray-200 text-sm font-medium text-gray-300 cursor-not-allowed">
                    <i class="fa-solid fa-angle-left"></i>
                </span>
                @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-3 py-2 border border-gray-200 text-sm font-medium text-gray-600 hover:bg-[#ff7700] hover:text-white transition-colors duration-200">
                    <i class="fa-solid fa-angle-left"></i>
                </a>
                @endif

                {{-- Pagination Elements --}}
                @php
                $currentPage = $paginator->currentPage();
                $lastPage = $paginator->lastPage();
                $elements = $paginator->links()->elements[0] ?? [];
                @endphp

                @foreach ($elements as $page => $url)
                @if ($page == $currentPage)
                <span aria-current="page" class="z-10 bg-[#ff7700] border-[#ff7700] text-white relative inline-flex items-center px-4 py-2 border text-sm font-medium shadow-sm">
                    {{ $page }}
                </span>
                @elseif ($page == 1 || $page == $lastPage || abs($page - $currentPage) <= 2)
                    <a href="{{ $url }}" class="bg-white border-gray-200 text-gray-600 hover:bg-[#ff7700] hover:text-white relative inline-flex items-center px-4 py-2 border text-sm font-medium transition-colors duration-200">
                    {{ $page }}
                    </a>
                    @elseif (abs($page - $currentPage) == 3)
                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-200 bg-gray-50 text-sm font-medium text-gray-500">
                        ...
                    </span>
                    @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-3 py-2 border border-gray-200 text-sm font-medium text-gray-600 hover:bg-[#ff7700] hover:text-white transition-colors duration-200">
                        <i class="fa-solid fa-angle-right"></i>
                    </a>
                    @else
                    <span class="relative inline-flex items-center px-3 py-2 border border-gray-200 text-sm font-medium text-gray-300 cursor-not-allowed">
                        <i class="fa-solid fa-angle-right"></i>
                    </span>
                    @endif
            </nav>
            @endif
        </div>
    </div>
</div>

@once
@push('scripts')
<script>
    // Helper function to update query parameters
    if (typeof updateQueryParam === 'undefined') {
        function updateQueryParam(key, value) {
            const url = new URL(window.location.href);
            url.searchParams.set(key, value);
            url.searchParams.set('page', '1'); // Reset to first page when changing per page
            return url.toString();
        }
    }
</script>
@endpush
@endonce