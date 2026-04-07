<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UrlUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'authentication_url' => ['required', 'url'],
            'server_url' => ['required', 'url'],
            'payment_base_url' => ['required', 'url'],
            'api_key' => ['required', 'string'],
            'reference_key' => ['required', 'string'],
        ];
    }
}
