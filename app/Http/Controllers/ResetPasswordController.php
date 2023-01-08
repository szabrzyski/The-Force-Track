<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmail;
use App\Mail\ResetPasswordEmail;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function resetPassword(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
            ],
            [
                'email.*' => 'Invalid e-mail address',
            ]
        );

        if ($validator->stopOnFirstFailure()->fails()) {
            return response()->json($validator->errors(), 427);
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
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
        // We'll provide false message even if the user is not found
        return response()->json('Success', 200);
    }

    public function resetPasswordFinish(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|max:255',
            'verificationCode' => 'required|string',
        ], [
            'password.*' => 'Password is invalid',
            'verificationCode.*' => 'Verification code is invalid or expired',
        ]);

        if ($validator->stopOnFirstFailure()->fails()) {
            return response()->json($validator->errors(), 427);
        }

        $verificationCode = Crypt::decryptString($request->verificationCode);

        $passwordReset = PasswordReset::where('verification_code', $verificationCode)->first();

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
