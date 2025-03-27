<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;

class LocaleMiddleware
{
    public function handle($request, Closure $next)
    {
        if (session()->has('app_locale')) {
            App::setLocale(session('app_locale'));
        }

        return $next($request);
    }
}
