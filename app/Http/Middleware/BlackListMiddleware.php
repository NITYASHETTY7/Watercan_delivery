<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class BlackListMiddleware
{
    public function handle($request, Closure $next)
    {
        $ipAddress = $request->ip();

        // Check if the user's IP address exists in the blacklists table
        $isBlocked = DB::table('blacklists')->where('ip_address', $ipAddress)->exists();

        // If IP address is blocked, redirect to the blocklisted page
        if ($isBlocked) {
            return redirect()->route('black-listed');
        }

        return $next($request);
    }
}
