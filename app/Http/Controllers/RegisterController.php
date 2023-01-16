<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Jobs\SendEmail;
use App\Mail\VerificationEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    // Create new user account

    public function createAccount(CreateUserRequest $request)
    {
        // Generate verification link

        $verificationCode = Hash::make(Str::random(64));

        $newUser = new User;
        $newUser->email = $request->email;
        $newUser->password = Hash::make($request->password);
        $newUser->verification_code = $verificationCode;

        if ($newUser->save()) {

            // Send e-mail with verification link

            $email = new VerificationEmail(Crypt::encryptString($verificationCode));
            SendEmail::dispatch($newUser->email, $email);

            $request->session()->put('userEmail', $newUser->email);

            return response()->json('Success', 200);
        } else {
            return response()->json('An error occured', 420);
        }
    }

    // Active user account

    public function activateAccount(Request $request, string $verificationCode)
    {
        $verificationCode = Crypt::decryptString($verificationCode);

        // Check if there's an user associated with provided verification code

        $user = User::where('verification_code', $verificationCode)->first();

        if ($user) {
            if (!$user->verificationCodeIsValid()) {

                // Verification code expired

                session(['alert' => json_encode(['message' => 'The activation link is expired.', 'type' => 'error'])]);

                return redirect()->route('login');
            } else {

                // Verification code is valid, activate the user account

                $user->verification_code = null;
                $user->email_verified_at = now();

                if ($user->save()) {
                    if ($request->session()->get('userEmail') === $user->email) {

                        // Login & redirect the user to homepage if his e-mail is in the session

                        $request->session()->forget('userEmail');
                        Auth::login($user);
                        $request->session()->regenerate();
                        session(['alert' => json_encode(['page' => 'issues', 'message' => 'Your account is active.', 'type' => 'success'])]);

                        return redirect()->route('issues');
                    }

                    // Redirect the user to login page if his e-mail is not in the session

                    session(['alert' => json_encode(['page' => 'login', 'message' => 'Your account is active, you can log in.', 'type' => 'success'])]);

                    return redirect()->route('login', ['email' => $user->email]);
                } else {

                    // An error occured when saving the user

                    session(['alert' => json_encode(['page' => 'login', 'message' => 'An error occuerd.', 'type' => 'error'])]);

                    return redirect()->route('login');
                }
            }
        } else {

            // There's no user associated with provided verification code

            session(['alert' => json_encode(['page' => 'login', 'message' => 'The activation link is invalid.', 'type' => 'error'])]);

            return redirect()->route('login');
        }
    }
}
