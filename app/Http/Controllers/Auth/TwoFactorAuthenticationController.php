<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TwoFactorAuthenticationController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        
        if ($user->two_factor_secret) {
            return view('auth.two-factor-recovery');
        }

        return view('auth.two-factor-setup');
    }

    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $user = $request->user();
        
        if (!$user->two_factor_secret) {
            throw ValidationException::withMessages([
                'code' => ['Two-factor authentication is not enabled.'],
            ]);
        }

        if (!$user->confirmTwoFactorAuthentication($request->code)) {
            throw ValidationException::withMessages([
                'code' => ['The provided code was invalid.'],
            ]);
        }

        return redirect()->route('two-factor.show')->with('status', 'Two-factor authentication enabled successfully!');
    }

    public function showQrCode(Request $request)
    {
        $user = $request->user();
        
        if ($user->two_factor_secret) {
            return redirect()->route('two-factor.show');
        }

        $user->createTwoFactorAuth();

        return view('auth.two-factor-setup', [
            'qrCode' => $user->twoFactorQrCodeSvg(),
            'secret' => decrypt($user->two_factor_secret),
        ]);
    }

    public function recoveryCodes(Request $request)
    {
        $request->user()->recoveryCodes();
        
        return back()->with('status', 'Recovery codes regenerated successfully!');
    }

    public function disable(Request $request)
    {
        $request->user()->disableTwoFactorAuth();
        
        return back()->with('status', 'Two-factor authentication disabled!');
    }

    public function showChallenge()
    {
        return view('auth.two-factor-challenge');
    }

    public function verifyChallenge(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $user = Auth::guard('web')->user();
        
        if ($user->hasValidTwoFactorCode($request->code)) {
            $request->session()->put('auth.password_confirmed_at', time());
            return redirect()->intended(route('dashboard'));
        }

        throw ValidationException::withMessages([
            'code' => ['The provided code was invalid.'],
        ]);
    }
}
