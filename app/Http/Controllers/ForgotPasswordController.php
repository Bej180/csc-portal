<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordNotification;
use App\Mail\UpdatedPasswordNotification;
use App\Models\ActivityLog;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User; // Replace with your user model path
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users'
        ], [
            'email.required' => 'Email must associated with account you want to reset not provided',
            'email.email' => 'Email address is invalid',
            'email.exists' => 'Email address not associated with any account'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(), 'errors' => $validator->errors()], 401);
        }

        $user = User::where('email', $request->email)->first();

        $requestedForReset = PasswordReset::where('email', $user->email)->first();

        $token = PasswordReset::generateToken();

        // expires after 60 mminutes
        $expires = now()->addMinutes(config('mail.password_reset.timeout', 60));

        // Check if user has already requested for password reset
        if ($requestedForReset) {

            // Update the record to any new token and new expiration time
            $requestedForReset->fill([
                'token' => $token,
                'expires' => $expires
            ])->save();
        } else {
            PasswordReset::create([
                'email' => $user->email,
                'token' => $token,
                'expires' => $expires
            ]);
        }


        // Send reset link to email address 
        $reset_link = route('password.reset', $token);
        $rest = Email(new ResetPasswordNotification($user, $reset_link), $user);

        return response()->json([
            'success' => 'We have emailed your password reset link!'
        ]);
    }


    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed',
            'token' => [
                'required',
                'exists:password_reset_tokens',
                function ($attribute, $value, $fail) {
                    $user = PasswordReset::where('token', '=', $value)->first();

                    if (time() > strtotime($user->expires)) {
                        $fail('Reset token has expired');
                    }
                }
            ]
        ], [
            'password.required' => 'Password is required',
            'password.confirmed' => 'Passwords do not match',
            'token.required' => 'Token is not provided',
            'token.exists' => 'Token has been terminated. Regenerate another token'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
                'errors'  => $validator->errors()
            ], 401);
        }
        $tokenUser = PasswordReset::where('token', $request->token)->first();
        $user = $tokenUser->user;


        $update = $user->fill([
            'password' => Hash::make($request->password)
        ])->save();

        if ($update) {
            $tokenUser->delete();

            // Notify the user about the change
            Email(new UpdatedPasswordNotification($user), $user);

            // log the change activity
            ActivityLog::logPasswordChangeActivity($user);

            return response()->json([
                'success' => 'Password has been reset successfully',
                'redirect' => '/login',
            ]);
        } else {
            return response()->json(['error' => 'Failed to reset password'], 401);
        }
    }



    public function passwordResetView(string $token, Request $request)
    {

        $account = PasswordReset::findAccountWithToken($token);


        $time = Carbon::parse($account->expires);
        $now = Carbon::now();

        $seconds = $now->diffInSeconds($time, false);
        $minutes = $now->diffInMinutes($time, false);

        return view('pages.auth.password_reset', compact('token', 'account', 'seconds', 'minutes'));
    }


    public function getTimer(Request $request) {
        $tokenAccount = null;
        $seconds = 0;
        $minutes = 0;

        $now = Carbon::now();

        if ($token = $request->token) {
            $tokenAccount = PasswordReset::where('token', '=', $token)->first();
            
            $expires = Carbon::parse($tokenAccount->expires);
            $seconds = $now->diffInSeconds($expires, false);
            $minutes = $now->diffInMinutes($expires, false);
        }


        $validator = Validator::make($request->all(), [
            'token' => [
                'required',
                'exists:password_reset_tokens',
                function ($attribute, $value, $fail) use ($seconds) {

                    if ($seconds < 1) {
                        $fail('Reset token has expired');
                    }

                }
            ],
        ], [
            'token.required' => 'Token must be provided',
            'token.exists' => 'Token does not exist or been timinated',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

      

        return compact('seconds');
    }
}
