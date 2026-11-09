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
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = ['en', 'fr', 'ar'];
        $locale = Session::get('locale', config('app.locale', 'en'));

        if (! in_array($locale, $supportedLocales, true)) {
            $locale = 'en';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
