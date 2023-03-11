<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the group.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return mixed
     */
    public function view(User $user, Group $group): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create groups.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the group.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return mixed
     */
    public function update(User $user, Group $group): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the group.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return mixed
     */
    public function delete(User $user, Group $group): bool
    {
        return $user->isAdmin();
    }
}
