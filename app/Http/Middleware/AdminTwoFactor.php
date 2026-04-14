<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminTwoFactor
{
    public function handle(Request $request, Closure $next)
    {
        $admin = Auth::guard('admin')->user();

        if ($admin && $admin->hasTwoFactor() && ! $request->session()->get('admin_2fa_verified')) {
            return redirect()->route('admin.2fa');
        }

        return $next($request);
    }
}
