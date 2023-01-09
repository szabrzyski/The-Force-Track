<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class AppController extends Controller
{

    // Initialize application

    public function initialize()
    {
        $user = Auth::user();

        $alert = session('alert');

        if ($alert) {
            session()->forget('alert');
        }

        return response()->json(['user' => $user, 'alert' => $alert], 200);
    }
}
