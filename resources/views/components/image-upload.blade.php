@props([
'name' => 'image',
'label' => 'Image',
'required' => false,
'currentImage' => null,
'helpText' => null,
'error' => null
])

@php
$uniqueId = $name . '_' . uniqid();
@endphp

<div>
    <label for="{{ $uniqueId }}" class="mb-2 block text-sm font-medium text-gray-700">
        {{ $label }}
        @if($required)
        <span class="text-red-500"> *</span>
        @endif
    </label>
    <div class="relative">
        <input
            id="{{ $uniqueId }}"
            name="{{ $name }}"
            type="file"
            accept="image/jpeg,image/png,image/jpg,image/webp"
            class="hidden image-upload-input"
            data-preview="{{ $uniqueId }}-preview"
            data-placeholder="{{ $uniqueId }}-placeholder"
            data-current-image="{{ $currentImage ? asset('storage/' . $currentImage) : '' }}"
            @if($required) required @endif>
        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-[#ff7700] transition cursor-pointer" onclick="document.getElementById('{{ $uniqueId }}').click()">
            <div id="{{ $uniqueId }}-placeholder" class="{{ $currentImage ? 'hidden' : '' }} space-y-2">
                <div class="flex justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-medium">Click to upload image</p>
                    <p class="text-xs text-gray-500 mt-1">PNG, JPG, JPEG or WEBP (Max. 2MB)</p>
                </div>
            </div>
            <img
                id="{{ $uniqueId }}-preview"
                src="{{ $currentImage ? asset('storage/' . $currentImage) : '' }}"
                class="{{ $currentImage ? '' : 'hidden' }} w-full h-64 object-cover rounded-lg"
                alt="Preview">
        </div>
        @if($required)
        <p id="{{ $uniqueId }}-error" class="mt-1 text-sm text-red-600 hidden"></p>
        @endif
    </div>
    @if($helpText)
    <p class="mt-1 text-xs text-gray-500">{{ $helpText }}</p>
    @endif
    @if($error)
    <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>

@once
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.image-upload-input').forEach(function(input) {
            const inputId = input.id;
            const errorElement = document.getElementById(inputId + '-error');

            input.addEventListener('change', function() {
                const previewId = this.dataset.preview;
                const placeholderId = this.dataset.placeholder;
                const currentImage = this.dataset.currentImage;
                const preview = document.getElementById(previewId);
                const placeholder = document.getElementById(placeholderId);

                if (this.files && this.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                    }

                    reader.readAsDataURL(input.files[0]);

                    // Clear error message when file is selected
                    if (errorElement) {
                        errorElement.classList.add('hidden');
                        input.setCustomValidity('');
                    }
                } else {
                    if (currentImage) {
                        preview.src = currentImage;
                        preview.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                    } else {
                        preview.src = '';
                        preview.classList.add('hidden');
                        placeholder.classList.remove('hidden');
                    }
                }
            });

            // Add custom validation message
            if (input.required) {
                input.addEventListener('invalid', function(e) {
                    e.preventDefault();
                    if (errorElement) {
                        errorElement.textContent = 'Please upload an event image';
                        errorElement.classList.remove('hidden');
                    }
                    this.setCustomValidity('Please upload an event image');

                    // Scroll to the image upload field
                    this.closest('div').scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                });

                input.addEventListener('input', function() {
                    if (errorElement && this.files.length > 0) {
                        errorElement.classList.add('hidden');
                    }
                    this.setCustomValidity('');
                });
            }
        });
    });
</script>
@endpush
@endonce