<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IssueController extends Controller
{
    public function initialize()
    {
        $statuses = Status::orderByDesc('default')->get();

        return response()->json($statuses, 200);
    }

    public function loadIssues(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'selectedStatuses' => 'sometimes|present|array|exists:statuses,id',
            ],
            [
                'selectedStatuses.*' => 'Invalid selected statuses',
            ]
        );

        if ($validator->stopOnFirstFailure()->fails()) {
            return response()->json($validator->errors(), 427);
        }

        $user = $request->user();
        $selectedStatuses = $request->selectedStatuses;

        $issues = Issue::when($selectedStatuses !== null, function ($query) use ($selectedStatuses) {
            // Get only those issues with selected statuses

            $query->whereIn('status_id', $selectedStatuses);
        })->when(! $user->isAdmin(), function ($query) use ($user) {
            // If the user is admin, get all issues. Otherwise get only those issues owned by the user.

            $query->where('user_id', $user->id);
        })->with(['category', 'status'])->orderByDesc('updated_at')->paginate(24)->onEachSide(1);

        return response()->json($issues, 200);
    }
}
