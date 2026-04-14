<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use PragmaRX\Google2FA\Google2FA;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $key = 'admin-login:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "Trop de tentatives. Réessayez dans {$seconds} secondes.",
            ])->withInput($request->only('email'));
        }

        if (Auth::guard('admin')->attempt($request->only('email', 'password'))) {
            RateLimiter::clear($key);
            $request->session()->regenerate();

            $admin = Auth::guard('admin')->user();
            $admin->update(['last_login_at' => now()]);
            $request->session()->put('admin_last_activity', time());

            AdminLogService::log('login', 'auth', null, ['email' => $admin->email]);

            if ($admin->hasTwoFactor()) {
                return redirect()->route('admin.2fa');
            }

            return redirect()->route('admin.dashboard');
        }

        RateLimiter::hit($key, 60);

        return back()->withErrors([
            'email' => 'Identifiants incorrects.',
        ])->withInput($request->only('email'));
    }

    public function show2fa()
    {
        return view('admin.auth.two-factor');
    }

    public function verify2fa(Request $request)
    {
        $request->validate(['code' => 'required|digits:6']);

        $admin = Auth::guard('admin')->user();
        $google2fa = new Google2FA();

        if ($google2fa->verifyKey($admin->two_factor_secret, $request->code)) {
            $request->session()->put('admin_2fa_verified', true);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['code' => 'Code 2FA invalide.']);
    }

    public function logout(Request $request)
    {
        AdminLogService::log('logout', 'auth');

        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
