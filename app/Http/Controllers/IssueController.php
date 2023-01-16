<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddIssueCommentRequest;
use App\Http\Requests\AddIssueRequest;
use App\Http\Requests\LoadIssuesRequest;
use App\Http\Requests\UpdateIssueStatusRequest;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Issue;
use App\Models\Status;
use App\Notifications\IssueStatusChanged;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    /**
     * Initialize page for listing issues.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function initializeIssuesIndexPage()
    {
        $statuses = Status::orderByDesc('default')->get();

        return response()->json($statuses, 200);
    }

    /**
     * Initialize page for adding issues.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function initializeAddIssuePage()
    {
        $categories = Category::all();

        return response()->json($categories, 200);
    }

    /**
     * Initialize page for issue details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function initializeIssueDetailsPage(Request $request, Issue $issue)
    {
        $statuses = Status::all();

        return response()->json(['issue' => $issue->load(['category', 'status', 'comments']), 'statuses' => $statuses], 200);
    }

    /**
     * Get issues with specified statuses.
     *
     * @param  LoadIssuesRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadIssues(LoadIssuesRequest $request)
    {
        $user = $request->user();
        $selectedStatuses = $request->selectedStatuses;

        $issues = Issue::when($selectedStatuses !== null, function ($query) use ($selectedStatuses) {
            $query->whereIn('status_id', $selectedStatuses);
        })->when(! $user->isAdmin(), function ($query) use ($user) {
            // If the user is admin, get all issues. Otherwise, get only those issues owned by the user.

            $query->where('user_id', $user->id);
        })->with(['category', 'status'])->orderByDesc('updated_at')->paginate(24)->onEachSide(1);

        return response()->json($issues, 200);
    }

    /**
     * Add new issue.
     *
     * @param  AddIssueRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addIssue(AddIssueRequest $request)
    {
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

    /**
     * Update issue status.
     *
     * @param  UpdateIssueStatusRequest  $request
     * @param  Issue  $issue
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateIssueStatus(UpdateIssueStatusRequest $request, Issue $issue)
    {
        $newStatus = $request->newStatus;

        // Check if the new status differs from the current status

        if ($issue->status_id == $newStatus) {
            return response()->json('Issue already has this status', 420);
        }

        $status = Status::where('id', $newStatus)->firstOrFail();

        $issue->status()->associate($status);

        if ($issue->save()) {
            // Send a notification to the author of the issue

            $issue->user->notify(new IssueStatusChanged($status->name, $issue->id));

            return response()->json('Success', 200);
        } else {
            return response()->json('An error occured', 420);
        }
    }

    /**
     * Add new comment to the issue.
     *
     * @param  AddIssueCommentRequest  $request
     * @param  Issue  $issue
     * @return \Illuminate\Http\JsonResponse
     */
    public function addIssueComment(AddIssueCommentRequest $request, Issue $issue)
    {
        $comment = new Comment;
        $comment->issue()->associate($issue);
        $comment->user()->associate($request->user());
        $comment->comment = $request->comment;

        if ($comment->save()) {
            return response()->json($comment, 200);
        } else {
            return response()->json('An error occured', 420);
        }
    }
}
