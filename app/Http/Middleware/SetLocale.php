<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $available = config('app.available_locales', ['fr', 'de', 'it', 'en']);

        $locale = $request->session()->get('locale')
            ?? $request->get('lang')
            ?? $request->getPreferredLanguage($available)
            ?? config('app.locale', 'fr');

        if (! in_array($locale, $available)) {
            $locale = config('app.locale', 'fr');
        }

        app()->setLocale($locale);
        $request->session()->put('locale', $locale);

        return $next($request);
    }
}
