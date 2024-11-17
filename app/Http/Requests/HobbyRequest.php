<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class HobbyRequest extends FormRequest
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
            'user_id'   => 'required_if:is_admin,true|exists:users,id',
            'hobbies'   => 'required|array',
            'hobbies.*' => 'required|string|max:255'
        ];
    }

    /**Check if logged in user is admin then user_id is required other wise optional */
    protected function prepareForValidation(): void
    {
        // Add 'is_admin' dynamically based on the current user role
        $this->merge([
            'is_admin' => auth()->check() && auth()->user()->role->name === 'admin',
        ]);
    }

     /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $response = error(__('messages.validation_failed'), $validator->errors(), 'validation');

        throw new HttpResponseException($response);
    }
}
