<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if locale is provided in URL
        if ($request->has('lang')) {
            $locale = $request->get('lang');
            if (in_array($locale, ['ar', 'en', 'tr'])) {
                Session::put('locale', $locale);
            }
        }

        // Get locale from session or use default
        $locale = Session::get('locale', config('app.locale', 'ar'));

        // Ensure locale is valid
        if (!in_array($locale, ['ar', 'en', 'tr'])) {
            $locale = 'ar';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
