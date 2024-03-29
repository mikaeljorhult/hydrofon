<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class UpdateLastLoggedInAt
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $event->user->last_logged_in_at = now();
        $event->user->save();
    }
}
