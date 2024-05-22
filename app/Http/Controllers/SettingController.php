<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;

class SettingController extends Controller
{
    public function enableTwoFactorAuth(Request $request)
    {
        $google2fa = new Google2FA();
        $secretKey = $google2fa->generateSecretKey();

        // Save $secretKey to the user's record in the database

        $qrCode = $google2fa->getQRCodeInline(
            config('app.name'),
            auth()->user()->email,
            $secretKey
        );

        return view('auth.two-factor-setup', compact('qrCode', 'secretKey'));
    }
}
