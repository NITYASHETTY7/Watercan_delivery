<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class SsoLogin
{
    public function handle(Request $request, Closure $next)
    {
        $requestToken = $request->query('sso-token');
        $envToken = env('SSO_TOKEN');
        if ($requestToken && $requestToken != $envToken) {
            return "ACCESS BLOCKED!";
        }

        return $next($request);
    }
}
