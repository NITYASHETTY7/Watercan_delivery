<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class VerifySOSToken
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
        $sso_token = $request->sso_token;

        if (isset($sso_token) && $sso_token !== null) {
            $user = User::where('sso_token', $sso_token)->first();
            if ($user) {
                auth()->logout();
                // // Login the user using the ID.
                auth()->loginUsingId($user->id);
                
                // Use Laravel's helper to build a new query excluding 'sso_token'
                $filteredQuery = collect(request()->query())
                    ->forget('sso_token')
                    ->all();

                // Create the new URL without 'sso_token'
                $newUrl = request()->url() . '?' . http_build_query($filteredQuery);
                return $next($request);
                return redirect($newUrl);
            }else {
                // If the token is not valid, return an unauthorized error with a specific message
                abort(403, 'Invalid token.');
            }
        }

        // If the token is valid, continue to the next middleware/request handler
        return $next($request);
    }
}
