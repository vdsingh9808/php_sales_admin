<?php

namespace App\Http\Requests;

use App\Rules\ColorCode;
use Illuminate\Foundation\Http\FormRequest;

class ThemeStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:themes,name'],
            'logo' => ['required', 'file', 'mimes:png,jpg,jpeg,svg'],
            'banner_image' => ['required', 'file', 'mimes:png,jpg,jpeg,svg'],
            'menu_name' => ['required'],
            'menu_name.*' => ['string'],
            'button_primary' => ['required', 'string', new ColorCode],
            'button_secondary' => ['required', 'string', new ColorCode],
            'is_default' => ['nullable', 'boolean'],
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
