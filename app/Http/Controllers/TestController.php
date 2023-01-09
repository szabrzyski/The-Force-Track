<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{

    // Test method for development purposes

    public function test(Request $request)
    {
        $user = User::where('id', 1)->first();

        return $user->issues[0];
    }
}
