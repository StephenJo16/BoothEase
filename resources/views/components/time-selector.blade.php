@props(['name','id'=>null,'value'=>null,'required'=>false])
@php $id = $id ?? $name; @endphp
<input type="time" name="{{ $name }}" id="{{ $id }}" value="{{ $value ?? '' }}" {!! $required ? 'required' : '' !!} class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-[#ff7700] focus:border-[#ff7700] outline-none">