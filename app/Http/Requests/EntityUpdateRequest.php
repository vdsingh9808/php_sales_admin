<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EntityUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'theme_id' => ['required', 'integer', 'exists:themes,id'],
            'api_key' => ['nullable', 'string'],
            'reference_key' => ['nullable', 'string'],
        ];
    }
}
