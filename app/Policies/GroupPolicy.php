<?php

namespace Hydrofon\Policies;

use Hydrofon\User;
use Hydrofon\Group;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the group.
     *
     * @param  \Hydrofon\User  $user
     * @param  \Hydrofon\Group  $group
     * @return mixed
     */
    public function view(User $user, Group $group)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create groups.
     *
     * @param  \Hydrofon\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the group.
     *
     * @param  \Hydrofon\User  $user
     * @param  \Hydrofon\Group  $group
     * @return mixed
     */
    public function update(User $user, Group $group)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the group.
     *
     * @param  \Hydrofon\User  $user
     * @param  \Hydrofon\Group  $group
     * @return mixed
     */
    public function delete(User $user, Group $group)
    {
        return $user->isAdmin();
    }
}
