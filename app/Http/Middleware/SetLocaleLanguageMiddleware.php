<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SetLocaleLanguageMiddleware
{
     /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request                                                                          $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @param  string|null                                                                                       ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if the user has preferences and the language key exists
            if (isset($user->preferences['language'])) {
                $locale = $user->preferences['language'];
                App::setLocale($locale);
            }
        }

        return $next($request);
    }
}
