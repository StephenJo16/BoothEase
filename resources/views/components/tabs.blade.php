@props(['tabs', 'onclick' => null])

<div class="border-b border-gray-200">
    <nav class="flex">
        @foreach($tabs as $index => $tab)
        @php $active = isset($tab['active']) && $tab['active']; @endphp
        <button class="hover:cursor-pointer {{ $active ? 'bg-[#ff7700] text-white border-[#ff7700]' : 'text-gray-600 hover:text-gray-800 border-transparent hover:border-gray-300' }} px-6 py-3 font-medium border-b-2 transition-colors duration-200 {{ $index === 0 ? 'rounded-tl-lg' : '' }}" @if($onclick) onclick="{{ $onclick }}('{{ strtolower($tab['name']) }}')" @endif>
            {{ $tab['name'] }}
        </button>
        @endforeach
    </nav>
</div>