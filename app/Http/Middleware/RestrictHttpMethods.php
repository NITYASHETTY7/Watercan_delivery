<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RestrictHttpMethods
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
        $response = $next($request);
        if(env('APP_ENV') != "local"){
            if (!$response instanceof BinaryFileResponse) {
                $allowedMethods = ['GET','POST']; // Add your allowed methods
        
                if (!in_array($request->method(), $allowedMethods)) {
                    return response()->json(['error' => 'Not Allowed'], 404);
                }
            }
        }

        return $next($request);
    }
}
