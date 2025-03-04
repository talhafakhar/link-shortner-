<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class CreateShortUrlRequest extends FormRequest
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
            'original_url' => [
                'required',
                'url',
                'max:2048',
            ],
            'custom_slug' => [
                'nullable',
                'alpha_dash',
                'min:3',
                'max:20',
                Rule::unique('short_urls', 'slug'),
            ],
            'expires_at' => [
                'nullable',
                'date',
                'after:now',
            ],
        ];
    }
}
