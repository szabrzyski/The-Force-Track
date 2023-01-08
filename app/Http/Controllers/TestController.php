<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test(Request $request)
    {
        $user = User::where('id', 1)->first();
        return $user->issues[0];
    }
}
