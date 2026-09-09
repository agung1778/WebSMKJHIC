<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Daftar bahasa yang didukung.
     */
    protected array $supportedLocales = ['id', 'en'];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->session()->get('locale');

        if (!$locale || !in_array($locale, $this->supportedLocales)) {
            $locale = config('app.locale', 'id');
        }

        App::setLocale($locale);

        return $next($request);
    }
}