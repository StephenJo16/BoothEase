@props(['title' => '', 'subtitle' => null])

<section class="bg-white py-10 mb-6 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $title }}</h1>
        @if($subtitle)
        <p class="text-lg text-gray-600">{{ $subtitle }}</p>
        @endif
    </div>
</section>