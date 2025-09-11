@props(['name','id'=>null,'value'=>null,'required'=>false])
@php $id = $id; @endphp
<input type="date" id="{{ $id }}" value="{{ $value ?? '' }}" {!! $required ? 'required' : '' !!} class="hover:cursor-text w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none">