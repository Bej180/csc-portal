<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TwoFactorController extends Controller
{
    
    public function verify()
    {
        return view('pages.auth.2fa.verify');
    }

    public function postVerify(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|string|min:6|max:6',
        ]);

        if (auth()->user()->verifyCode($request->code)) {
            
            return redirect()->intended('dashboard'); // Or desired location
        }

        return back()->withErrors(['code' => 'Invalid 2FA code.']);
    }


    /**
     * Get the two-factor authentication confirmation view.
     */
    public function show(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->hasEnabledTwoFactorAuthentication()) {
            return back()->with('status', 'Two-factor authentication is already enabled');
        }

        if (! $user->two_factor_secret) {
            return back()->with('status', 'Two-factor authentication is not enabled');
        }

        return view('account.two-factor-confirmation.show', [
            'qrCodeSvg' => $user->twoFactorQrCodeSvg(),
            'setupKey' => $user->two_factor_secret,
        ]);
    }


    /**
     * Enable two-factor authentication for the user.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasEnabledTwoFactorAuthentication()) {
            return back()->with('status', 'Two-factor authentication is already enabled');
        }

        $user->enableTwoFactorAuthentication();

        return redirect()->route('account.two-factor-authentication.confirm.show');
    }



    /**
     * Confirm two-factor authentication for the user.
     */
    public function confirm(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $request->user()->confirmTwoFactorAuthentication($request->code);

        return redirect()
            ->route('account.two-factor-authentication.recovery-codes.index')
            ->with('status', 'Two-factor authentication successfully confirmed');
    }





    /**
 * Disable two-factor authentication for the user.
 */
public function destroy(Request $request): RedirectResponse
{
    $request->user()->disableTwoFactorAuthentication();

    return back()->with('status', 'Two-factor authentication disabled successfully');
}
}
