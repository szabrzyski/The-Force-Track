<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Issue;
use App\Models\Status;

class IssueController extends Controller
{
    public function initialize()
    {
        $statuses = Status::all();
        return response()->json($statuses, 200);
    }

    public function loadIssues(Request $request)
    {
      
       $issues = Issue::with('category')->paginate(24)->onEachSide(1);

        return response()->json($issues, 200);
    }


}
