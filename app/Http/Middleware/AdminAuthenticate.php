<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        $admin = Auth::guard('admin')->user();

        if (! $admin->isActive()) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'Votre compte est désactivé.']);
        }

        return $next($request);
    }
}
