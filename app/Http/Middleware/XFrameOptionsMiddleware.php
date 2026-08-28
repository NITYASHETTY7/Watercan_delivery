<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class XFrameOptionsMiddleware
{
    public function handle($request,Closure $next)
    {
        $response = $next($request);

        if(env('APP_ENV') != "local"){
        // Check if the response is not a BinaryFileResponse
            if (!$response instanceof BinaryFileResponse) {
                // Set X-Frame-Options header only for non-file responses
                $response->header('X-Frame-Options', 'DENY');
            }
        }

        return $response;
    }
}

