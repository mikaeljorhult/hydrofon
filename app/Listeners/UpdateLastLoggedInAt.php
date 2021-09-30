<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class UpdateLastLoggedInAt
{
    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $event->user->last_logged_in_at = now();
        $event->user->save();
    }
}
