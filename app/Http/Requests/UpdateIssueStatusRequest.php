<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIssueStatusRequest extends FormRequest
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
            'newStatus' => 'required|integer|numeric|exists:App\Models\Status,id',
        ];
    }

    public function messages(): array
    {
        return [
            'newStatus.*' => 'Invalid status',
        ];
    }
}
