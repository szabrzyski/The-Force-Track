<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginUserRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Check if user login data are in correct format.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|min:1|max:255',
            'password' => 'required|string|min:8|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'email.*' => 'Invalid e-mail address',
            'password.*' => 'Invalid password',
        ];
    }
}
