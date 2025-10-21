@props(['message' => 'Loading...', 'overlay' => false, 'size' => 'md'])

@php
$sizeClasses = [
'sm' => 'w-8 h-8 border-4',
'md' => 'w-16 h-16 border-4',
'lg' => 'w-24 h-24 border-8',
];
$spinnerSize = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

@if($overlay)
<!-- Full Page Overlay Loader -->
<div class="fixed inset-0 bg-white z-50 flex items-center justify-center">
    <div class="flex flex-col items-center space-y-6">
        <!-- Spinner -->
        <div class="{{ $spinnerSize }} border-gray-200 border-t-[#ff7700] rounded-full animate-spin"></div>

        <!-- Loading Message -->
        @if($message)
        <p class="text-gray-700 font-medium text-lg">{{ $message }}</p>
        @endif
    </div>
</div>
@else
<!-- Inline Loader -->
<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center py-8 space-y-4']) }}>
    <!-- Spinner -->
    <div class="{{ $spinnerSize }} border-gray-200 border-t-[#ff7700] rounded-full animate-spin"></div>

    <!-- Loading Message -->
    @if($message)
    <p class="text-gray-700 font-medium text-lg">{{ $message }}</p>
    @endif
</div>
@endif