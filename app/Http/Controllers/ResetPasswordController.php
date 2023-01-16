<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordFinishRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Jobs\SendEmail;
use App\Mail\ResetPasswordEmail;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /**
     * Send password reset link to the user.
     *
     * @param  ResetPasswordRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // There's user associated with provided e-mail

            // Generate verification code

            $verificationCode = Hash::make(Str::random(64));

            $passwordReset = new PasswordReset;
            $passwordReset->email = $user->email;
            $passwordReset->verification_code = $verificationCode;

            if ($passwordReset->save()) {
                $email = new ResetPasswordEmail($user->email, Crypt::encryptString($verificationCode));
                SendEmail::dispatch($user->email, $email);
            } else {
                return response()->json('An error occured', 420);
            }
        }

        // User associated with provided e-mail doesn't exists, but we'll provide false message anyway

        return response()->json('Success', 200);
    }

    /**
     * Update user password.
     *
     * @param  ResetPasswordFinishRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPasswordFinish(ResetPasswordFinishRequest $request)
    {
        $verificationCode = Crypt::decryptString($request->verificationCode);

        $passwordReset = PasswordReset::where('verification_code', $verificationCode)->first();

        // Check if verification code is valid

        if ($passwordReset) {
            if ($passwordReset->verificationCodeIsValid()) {
                $user = User::where('email', $passwordReset->email)->first();
                if ($user) {
                    $user->password = Hash::make($request->password);
                    if ($user->save()) {
                        $passwordReset->delete();

                        return response()->json('Success', 200);
                    } else {
                        return response()->json('An error occured', 420);
                    }
                }
            }
        }

        return response()->json('Verification code is invalid or expired', 420);
    }
}
