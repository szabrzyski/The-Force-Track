<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoadIssuesRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Check if selected statuses are valid.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'selectedStatuses' => 'sometimes|present|array|exists:App\Models\Status,id',
        ];
    }

    public function messages(): array
    {
        return [
            'selectedStatuses.*' => 'Invalid selected statuses',
        ];
    }
}
