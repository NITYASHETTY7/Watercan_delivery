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

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */
    use ResetsPasswords;

/**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */

    protected $redirectTo = RouteServiceProvider::HOME;
    protected function reset(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'token' => 'required|string',
                'password' => 'required|string|confirmed|min:6',
            ]);

            // 1. Check token exists in your custom table
            $tokenRecord = DB::table('password_resets')
                ->where('email', $request->email)
                ->where('token', $request->token)
                ->first();

            if (!$tokenRecord) {
                return back()->with('error', 'Invalid or expired token.');
            }

            // 2. Reset the password
            $user = User::where('email', $request->email)->first();
            $user->password = Hash::make($request->password);
            $user->setRememberToken(Str::random(60)); // Refresh remember_token
            $user->save();

            // 3. Delete the token (optional but recommended)
            DB::table('password_resets')->where('email', $request->email)->delete();

            // 4. Auto-login
            // auth()->loginUsingId($user->id);

            return redirect(url('/'))->with('success', 'Password Updated!');
        } catch (\Throwable $th) {
            return back()->with('error', 'There was an error: ' . $th->getMessage());
        }
    }
}
