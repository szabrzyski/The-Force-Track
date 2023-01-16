<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    // Login the user

    public function loginUser(LoginUserRequest $request)
    {

        $email = $request->email;
        $password = $request->password;

        if (Auth::attemptWhen(['email' => $email, 'password' => $password], function ($user) {

            // Allow only verified users to login

            return $user->isVerified();
        }, true)) {
            $user = Auth::user();
            $request->session()->regenerate();

            // Add redirect link if user intended to visit protected page

            $intendedUrl = redirect()->getIntendedUrl();
            $redirectTo = $intendedUrl ? Str::after($intendedUrl, route('issues', [], true)) : route('issues', [], false);

            if ($redirectTo == "") {
                $redirectTo = "/";
            }

            return response()->json(['redirectTo' => $redirectTo, 'user' => $user], 200);
        }

        return response()->json('Invalid login data', 420);
    }

    // Logout the user

    public function logoutUser(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json('Success', 200);
    }
}
