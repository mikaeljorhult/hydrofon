<?php

namespace App\Policies;

use App\Checkin;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CheckinPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create checkins.
     *
     * @param \App\User $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the checkin.
     *
     * @param \App\User    $user
     * @param \App\Checkin $checkin
     *
     * @return mixed
     */
    public function delete(User $user, Checkin $checkin)
    {
        return $user->isAdmin();
    }
}
