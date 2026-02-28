<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:30',
                'regex:/^[a-zA-Z0-9\s]+$/', // allows letters, numbers, and spaces
            ],
            'description' => [
                'required',
                'string',
                'max:4000',
                'regex:/^[a-zA-Z0-9\s.;:!?\-]+$/', // allows letters, numbers, spaces, and common punctuation
            ],
        ];
    }
}
