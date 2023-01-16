<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Check if account details are in correct format.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|min:1|max:255|unique:users,email',
            'password' => 'required|min:8|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'E-mail address already exists',
            'email.*' => 'Invalid e-mail address',
            'password.*' => 'Invalid password',
        ];
    }
}
