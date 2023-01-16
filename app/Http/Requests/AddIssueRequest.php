<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddIssueRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Check if new issue details are valid.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'subject' => 'required|string|min:1|max:255',
            'description' => 'required|string|min:1|max:65535',
            'category' => 'required|integer|numeric|exists:App\Models\Category,id',
        ];
    }

    public function messages(): array
    {
        return [
            'subject.*' => 'Invalid subject',
            'description.*' => 'Invalid description',
            'category.*' => 'Invalid category',
        ];
    }
}
