@props(['title' => '', 'subtitle' => null])
{{-- Contoh di dalam file components/header.blade.php --}}
<head>
    {{-- ... kode lain yang sudah ada seperti <meta>, <title>, dll. --}}

    {{-- TAMBAHKAN 3 BARIS INI --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">

    {{-- ... kode lain yang sudah ada --}}
</head>
<section class="bg-white py-10 mb-6 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $title }}</h1>
        @if($subtitle)
        <p class="text-lg text-gray-600">{{ $subtitle }}</p>
        @endif
    </div>
</section>