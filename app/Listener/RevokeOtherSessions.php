<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RevokeOtherSessions
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $user = $event->user;

        // Revoke other sessions for the same user
        if(UserRole($user->id)['name'] != 'admin'){
            DB::table('sessions')
                ->where('user_id', $user->id)
                ->where('id', '<>', session()->getId())
                ->delete();
        }
    }
}
