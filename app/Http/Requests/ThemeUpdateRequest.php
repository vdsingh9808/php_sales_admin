<?php

namespace App\Http\Requests;

use App\Models\Theme;
use App\Rules\AtLeastOneDefaultTheme;
use App\Rules\ColorCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ThemeUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $id = $this->route('theme');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Theme::class)->ignore($this->route('theme')),
            ],
            'logo' => ['nullable', 'file', 'mimes:png,jpg,jpeg,svg'],
            'banner_image' => ['nullable', 'file', 'mimes:png,jpg,jpeg,svg'],
            'menu_name' => ['required', 'array'],
            'menu_name.*' => ['string'],
            'button_primary' => ['required', 'string', new ColorCode],
            'button_secondary' => ['required', 'string', new ColorCode],
            'is_default' => ['nullable', 'boolean', new AtLeastOneDefaultTheme($id)],
            'general_heading' => ['required', 'string', new ColorCode],
            'general_body' => ['required', 'string', new ColorCode],
            'general_icons' => ['required', 'string', new ColorCode],
            'general_background' => ['required', 'string', new ColorCode],
            'table_heading' => ['required', 'string', new ColorCode],
            'table_title' => ['required', 'string', new ColorCode],
            'table_body' => ['required', 'string', new ColorCode],
            'header_background' => ['required', 'string', new ColorCode],
            'header_text' => ['required', 'string', new ColorCode],
            'navbar_text' => ['required', 'string', new ColorCode],
            'navbar_background' => ['required', 'string', new ColorCode],
        ];
    }
}
