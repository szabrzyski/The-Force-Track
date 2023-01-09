<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Issue;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Notifications\IssueStatusChanged;

class IssueController extends Controller
{
    // Initialize page for listing issues

    public function initializeIssuesIndexPage()
    {
        $statuses = Status::orderByDesc('default')->get();

        return response()->json($statuses, 200);
    }

    // Initialize page for adding issues

    public function initializeAddIssuePage()
    {
        $categories = Category::all();

        return response()->json($categories, 200);
    }

    // Initialize page for issue details

    public function initializeIssueDetailsPage(Request $request, Issue $issue)
    {
        $statuses = Status::all();

        return response()->json(['issue' => $issue->load(['category', 'status']), 'statuses' => $statuses], 200);
    }

    // Get issues with specified statuses

    public function loadIssues(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'selectedStatuses' => 'sometimes|present|array|exists:App\Models\Status,id',
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
            $query->whereIn('status_id', $selectedStatuses);
        })->when(!$user->isAdmin(), function ($query) use ($user) {

            // If the user is admin, get all issues. Otherwise, get only those issues owned by the user.

            $query->where('user_id', $user->id);
        })->with(['category', 'status'])->orderByDesc('updated_at')->paginate(24)->onEachSide(1);

        return response()->json($issues, 200);
    }

    // Add new issue

    public function addIssue(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'subject' => 'required|string|min:1|max:255',
                'description' => 'required|string|min:1|max:65535',
                'category' => 'required|integer|numeric|exists:App\Models\Category,id',
            ],
            [
                'subject.*' => 'Invalid subject',
                'description.*' => 'Invalid description',
                'category.*' => 'Invalid category',
            ]
        );

        if ($validator->stopOnFirstFailure()->fails()) {
            return response()->json($validator->errors(), 427);
        }

        $status = Status::where('name', 'Open')->firstOrFail();

        $issue = new Issue;
        $issue->user()->associate($request->user());
        $issue->status()->associate($status);
        $issue->category_id = $request->category;
        $issue->subject = $request->subject;
        $issue->description = $request->description;

        if ($issue->save()) {
            return response()->json('Success', 200);
        } else {
            return response()->json('An error occured', 420);
        }
    }

    // Update issue status

    public function updateIssueStatus(Request $request, Issue $issue)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'newStatus' => 'required|integer|numeric|exists:App\Models\Status,id',
            ],
            [
                'newStatus.*' => 'Invalid status',
            ]
        );

        // Check if the new status differs from the current status

        $newStatus = $request->newStatus;

        $validator->after(function ($validator) use ($issue, $newStatus) {
            if ($issue->status_id == $newStatus) {
                $validator->errors()->add(
                    'newStatus', 'Issue already has this status'
                );
            }
        });

        if ($validator->stopOnFirstFailure()->fails()) {
            return response()->json($validator->errors(), 427);
        }

        $status = Status::where('id',$newStatus)->firstOrFail();

        $issue->status()->associate($status);

        if ($issue->save()) {

            // Send a notification to the author of the issue

            $issue->user->notify(new IssueStatusChanged($status->name, $issue->id));

            return response()->json('Success', 200);
        } else {
            return response()->json('An error occured', 420);
        }
    }
}
