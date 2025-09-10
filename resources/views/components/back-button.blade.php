@props(['text' => null, 'url' => null])

<div class="inline-flex sm:ml-4 xl:ml-8 ">
    @if($url)
    <a href="{{ $url }}" class="inline-flex items-center text-gray-600 hover:text-[#ff7700] px- py-2 text-xl rounded-md transition-colors duration-200 group" aria-label="Go back">
        <i class="fas fa-arrow-left {{ $text ? 'mr-2' : '' }} group-hover:text-[#ff7700][] transition-colors duration-200"></i>
        @if($text)
        <span class="font-medium">{{ $text }}</span>
        @endif
    </a>
    @else
    <button onclick="history.back()" class="inline-flex items-center text-gray-600 hover:text-[#ff7700] px-4 py-2 text-xl rounded-md transition-colors duration-200 group" aria-label="Go back">
        <i class="fas fa-arrow-left {{ $text ? 'mr-2' : '' }} group-hover:text-[#ff7700] transition-colors duration-200"></i>
        @if($text)
        <span class="font-medium">{{ $text }}</span>
        @endif
    </button>
    @endif
</div>