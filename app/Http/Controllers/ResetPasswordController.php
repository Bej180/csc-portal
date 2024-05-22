<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords; // Import the ResetsPasswords trait
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    use ResetsPasswords; // Use the ResetsPasswords trait

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ];
    }

    protected function validationErrorMessages()
    {
        return [];
    }

    protected function resetPassword($user, $password)
    {
        $user->password = bcrypt($password);
        $user->reset_token = null;
        $user->reset_token_expires_at = null;
        $user->save();
    }

    protected function sendResetResponse($response)
    {
        return redirect('/login')->with('status', trans($response));
    }

    protected function sendResetFailedResponse(Request $request, $response)
    {
        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => trans($response)]);
    }
}
