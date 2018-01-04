<?php

namespace Hydrofon\Policies;

use Hydrofon\Checkin;
use Hydrofon\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CheckinPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create checkins.
     *
     * @param  \Hydrofon\User $user
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
     * @param  \Hydrofon\User $user
     * @param  \Hydrofon\Checkin $checkin
     *
     * @return mixed
     */
    public function delete(User $user, Checkin $checkin)
    {
        return $user->isAdmin();
    }
}
