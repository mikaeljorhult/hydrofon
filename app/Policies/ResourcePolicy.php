<?php

namespace App\Policies;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResourcePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can see the resource in listings.
     *
     * @param \App\Models\User     $user
     * @param \App\Models\Resource $resource
     *
     * @return mixed
     */
    public function list(User $user, Resource $resource)
    {
        return $user->isAdmin() ||
               $resource->groups->count() === 0 ||
               $user->groups->intersect($resource->groups)->count() > 0;
    }

    /**
     * Determine whether the user can view the resource.
     *
     * @param \App\Models\User     $user
     * @param \App\Models\Resource $resource
     *
     * @return mixed
     */
    public function view(User $user, Resource $resource)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create resources.
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
     * Determine whether the user can update the resource.
     *
     * @param \App\Models\User     $user
     * @param \App\Models\Resource $resource
     *
     * @return mixed
     */
    public function update(User $user, Resource $resource)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the resource.
     *
     * @param \App\Models\User     $user
     * @param \App\Models\Resource $resource
     *
     * @return mixed
     */
    public function delete(User $user, Resource $resource)
    {
        return $user->isAdmin();
    }
}
