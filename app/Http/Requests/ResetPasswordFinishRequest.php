<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordFinishRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Check if new issue status is valid.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'password' => 'required|string|min:8|max:255',
            'verificationCode' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'password.*' => 'Password is invalid',
            'verificationCode.*' => 'Verification code is invalid or expired',
        ];
    }
}
