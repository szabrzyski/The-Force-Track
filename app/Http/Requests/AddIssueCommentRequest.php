<?php

namespace App\Http\Requests;

use App\Models\Issue;
use Illuminate\Foundation\Http\FormRequest;

class AddIssueCommentRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * Check if user is allowed to see the issue
     *
     * @return array
     */
    public function authorize(): bool
    {
        $issue = Issue::findOrFail($this->route('issue'));

        return $this->user()->can('show', $issue);
    }

    /**
     * Check if new issue status is valid.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'comment' => 'required|string|min:1|max:65535',
        ];
    }

    public function messages(): array
    {
        return [
            'comment.*' => 'Invalid comment',
        ];
    }
}
