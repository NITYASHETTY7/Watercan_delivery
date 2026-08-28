<?php
/**
 *
 * @category ZStarter
 *
 * @ref     Book My Water product
 * @author  <Book My Water info@bookmywater.come>
 * @license <https://watercane-dev.dze-labs.in Book My Water>
 * @version <zStarter: 202402-V2.0>
 * @link    <https://watercane-dev.dze-labs.in>
 */


namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */

    protected function redirectTo($request)
    {
        if (!auth()->check()) {
            
            if ($request->is('admin/*')) {
                return route('admin.login');
            } else if ($request->is('member/*')) {
                return url('member/login');
            } else {
                return url('user/login');
            }
        }
        return null;
    }
}
