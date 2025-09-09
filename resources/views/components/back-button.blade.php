@props(['text' => null, 'url' => null])

<div class="inline-flex">
    @if($url)
    <a href="{{ $url }}" class="inline-flex items-center text-gray-600 hover:text-[#ff7700] transition-colors duration-200 group" aria-label="Go back">
        <i class="fas fa-arrow-left {{ $text ? 'mr-2' : '' }} group-hover:text-[#ff7700] transition-colors duration-200"></i>
        @if($text)
        <span>{{ $text }}</span>
        @endif
    </a>
    @else
    <button onclick="history.back()" class="inline-flex items-center text-gray-600 hover:text-[#ff7700] transition-colors duration-200 group" aria-label="Go back">
        <i class="fas fa-arrow-left {{ $text ? 'mr-2' : '' }} group-hover:text-[#ff7700] transition-colors duration-200"></i>
        @if($text)
        <span>{{ $text }}</span>
        @endif
    </button>
    @endif
</div>