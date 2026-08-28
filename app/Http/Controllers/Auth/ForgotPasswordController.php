<?php
/**
 *
 * @category zStarter
 *
 * @ref Book My Water Product
 * @author <Book My Water  info@watercane.come>
 * @license <https://watercane-dev.dze-labs.in Book My Water>
 * @version <zStarter: 1.2.0>
 * @link <https://watercane-dev.dze-labs.in>
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Notifications\ResetPasswordCustom;


class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    // use SendsPasswordResetEmails;
    public function sendResetLinkEmail(Request $request)
    {
        /* 1 ─────── Validate the incoming email ─────── */
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        /* 2 ─────── Generate a token and persist it ─── */
        $token = Str::random(64);                            // raw token
        DB::table('password_resets')                         // your table name
            ->updateOrInsert(
                ['email' => $request->email],
                ['token' => $token, 'created_at' => now()]   // updated_at / deleted_at nullable
            );

        /* 3 ─────── Send the e‑mail via CyberPanel SMTP ─ */
        $user = User::where('email', $request->email)->first();
        $user->remember_token = $token;
        $user->save();

        $user->notify(new ResetPasswordCustom($token));      // see next snippet

        /* 4 ─────── Standard Laravel response ────────── */
       return back()
       ->with('status', trans('passwords.sent'))   // already there
       ->with('flash_success', 'Password‑reset instructions emailed ✅');
    }
}
