<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow all users to make booking requests
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'booth_id' => 'required|exists:booths,id',
            'full_name' => 'required|string|max:255',
            'business_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'product_pictures' => 'required|array|min:1|max:3',
            'product_pictures.*' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'booth_id.required' => 'Please select a booth.',
            'booth_id.exists' => 'The selected booth is not available.',
            'full_name.required' => 'Full name is required.',
            'business_name.required' => 'Business/Company name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'phone.required' => 'Phone number is required.',
            'product_pictures.required' => 'At least one product picture is required.',
            'product_pictures.min' => 'At least one product picture is required.',
            'product_pictures.max' => 'You can upload a maximum of 3 product pictures.',
            'product_pictures.*.required' => 'Each product picture is required.',
            'product_pictures.*.image' => 'Product pictures must be images.',
            'product_pictures.*.mimes' => 'Product pictures must be JPG, JPEG, or PNG files.',
            'product_pictures.*.max' => 'Each product picture must not exceed 5MB.',
        ];
    }
}
