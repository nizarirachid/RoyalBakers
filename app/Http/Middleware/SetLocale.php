<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale', config('app.locale', 'ar'));

        if (auth()->check() && auth()->user()->preferred_language) {
            $locale = auth()->user()->preferred_language;
        }

        $supported = ['ar', 'fr', 'en'];
        if (!in_array($locale, $supported)) {
            $locale = 'ar';
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
