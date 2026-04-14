<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;

class AdminSessionTimeout
{
    public function handle(Request $request, Closure $next)
    {
        $timeout = (int) Setting::get('admin_session_timeout', 120) * 60;
        $lastActivity = $request->session()->get('admin_last_activity');

        if ($lastActivity && (time() - $lastActivity) > $timeout) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();

            return redirect()->route('admin.login')
                ->withErrors(['email' => 'Session expirée. Veuillez vous reconnecter.']);
        }

        $request->session()->put('admin_last_activity', time());

        return $next($request);
    }
}
