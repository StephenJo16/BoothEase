<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBoothRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event_id' => ['required', 'integer', 'exists:events,id'],
            'layout_json' => ['required', 'string', 'json'],
            'replace_existing' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'layout_json.json' => 'The layout_json field must contain valid JSON data exported from the designer.',
        ];
    }
}