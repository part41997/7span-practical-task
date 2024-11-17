<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email'     => 'required|email|exists:users,email',
            'password'  => 'required|string|min:6',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required'    => 'The email address is required.',
            'email.email'       => 'Please provide a valid email address.',
            'email.exists'      => 'This email does not exist in our records.',
            'password.required' => 'Password is required.',
            'password.min'      => 'Password must be at least 6 characters long.',
        ];
    }
}
