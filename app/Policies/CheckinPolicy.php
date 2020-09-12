<?php

namespace App\Policies;

use App\Models\Checkin;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CheckinPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create checkins.
     *
     * @param \App\Models\User $user
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
     * @param \App\Models\User    $user
     * @param \App\Models\Checkin $checkin
     *
     * @return mixed
     */
    public function delete(User $user, Checkin $checkin)
    {
        return $user->isAdmin();
    }
}
