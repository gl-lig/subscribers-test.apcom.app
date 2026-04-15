<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminLogService;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Google2FA;

class AdminProfileController extends Controller
{
    public function index()
    {
        return view('admin.profile.index', [
            'admin' => Auth::guard('admin')->user(),
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $admin = Auth::guard('admin')->user();

        if (! Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.']);
        }

        $admin->update(['password' => Hash::make($request->password)]);

        AdminLogService::log('password_changed', 'profile');

        return back()->with('success', 'Mot de passe mis à jour.');
    }

    public function setup2fa()
    {
        $admin = Auth::guard('admin')->user();
        $google2fa = new Google2FA();

        $secret = $google2fa->generateSecretKey();

        session(['2fa_setup_secret' => $secret]);

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $admin->email,
            $secret
        );

        $renderer = new ImageRenderer(
            new RendererStyle(250),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrCodeSvg = $writer->writeString($qrCodeUrl);

        return view('admin.profile.setup-2fa', [
            'admin' => $admin,
            'secret' => $secret,
            'qrCodeSvg' => $qrCodeSvg,
        ]);
    }

    public function confirm2fa(Request $request)
    {
        $request->validate(['code' => 'required|digits:6']);

        $secret = session('2fa_setup_secret');

        if (! $secret) {
            return redirect()->route('admin.profile.index')->with('error', 'Session expirée. Recommencez la configuration.');
        }

        $google2fa = new Google2FA();

        if (! $google2fa->verifyKey($secret, $request->code)) {
            return back()->withErrors(['code' => 'Code invalide. Vérifiez et réessayez.']);
        }

        $admin = Auth::guard('admin')->user();
        $admin->update([
            'two_factor_secret' => $secret,
            'two_factor_confirmed_at' => now(),
        ]);

        session()->forget('2fa_setup_secret');
        $request->session()->put('admin_2fa_verified', true);

        AdminLogService::log('2fa_enabled', 'profile');

        return redirect()->route('admin.profile.index')->with('success', 'Authentification 2FA activée.');
    }

    public function disable2fa(Request $request)
    {
        $request->validate(['password' => 'required']);

        $admin = Auth::guard('admin')->user();

        if (! Hash::check($request->password, $admin->password)) {
            return back()->withErrors(['password' => 'Mot de passe incorrect.']);
        }

        $admin->update([
            'two_factor_secret' => null,
            'two_factor_confirmed_at' => null,
        ]);

        session()->forget('admin_2fa_verified');

        AdminLogService::log('2fa_disabled', 'profile');

        return redirect()->route('admin.profile.index')->with('success', 'Authentification 2FA désactivée.');
    }
}
