<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'old_password' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, $this->user()->password)) {
                        $fail('The old password is incorrect.');
                    }
                },
            ],
            'new_password' => [
                'required',
                'min:8',
                'string',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/',
            ],
            'confirm_password' => ['required', 'string', 'same:new_password'],
        ];
    }

    public function messages(): array
    {
        return [
            'old_password.required' => 'The old password field is required.',
            'new_password.required' => 'The new password field is required.',
            'new_password.string' => 'The new password must be a string.',
            'new_password.min' => 'The new password must be at least :min characters.',
            'new_password.regex' => 'The new password must contain at least one uppercase letter, one lowercase letter, one digit, and one special character (non-alphanumeric).',
            'new_password.not_in' => 'The new password must be different from the old password.',
            'confirm_password.required' => 'The confirm password field is required.',
            'confirm_password.string' => 'The confirm password must be a string.',
            'confirm_password.same' => 'The confirm password must match the new password.',
        ];
    }
}
