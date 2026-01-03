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
            'product_picture' => 'required|file|mimes:pdf|max:5120',
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
            'product_picture.required' => 'Product pictures are required.',
            'product_picture.file' => 'Product pictures must be a file.',
            'product_picture.mimes' => 'Product pictures must be a PDF file.',
            'product_picture.max' => 'Product pictures must not exceed 5MB.',
        ];
    }
}
