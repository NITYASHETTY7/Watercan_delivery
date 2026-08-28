<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SecureHeadersMiddleware
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
                $response->header('X-Content-Type-Options', 'nosniff');
                $response->header('X-Frame-Options', 'DENY');
                $response->header('X-XSS-Protection', '1; mode=block');
                $response->header('Content-Security-Policy', "frame-ancestors 'none'");

                $response->header('Content-Security-Policy', "default-src 'self'; script-src 'self' https://www.google.com/recaptcha/ https://www.gstatic.com/recaptcha/ 'unsafe-inline' 'unsafe-eval' https://code.jquery.com https://cdnjs.cloudflare.com https://www.google.com https://unpkg.com https://cdn.jsdelivr.net https://stackpath.bootstrapcdn.com style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com; img-src 'self' data: https://www.nafed-india.com https://upload.wikimedia.org https://ui-avatars.com; font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com data:; connect-src 'self' https://upload.wikimedia.org https://cdnjs.cloudflare.com; frame-src 'self' https://www.google.com/recaptcha/ https://recaptcha.google.com/recaptcha/; upgrade-insecure-requests;");

                
                $response->header('Strict-Transport-Security', 'max-age=63072000; includeSubDomains; preload');
                
            }
        }
        return $response;
    }
}
