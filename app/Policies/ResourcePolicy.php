<?php

namespace Hydrofon\Policies;

use Hydrofon\Resource;
use Hydrofon\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResourcePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can see the resource in listings.
     *
     * @param \Hydrofon\User     $user
     * @param \Hydrofon\Resource $resource
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
     * @param \Hydrofon\User     $user
     * @param \Hydrofon\Resource $resource
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
     * @param \Hydrofon\User $user
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
     * @param \Hydrofon\User     $user
     * @param \Hydrofon\Resource $resource
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
     * @param \Hydrofon\User     $user
     * @param \Hydrofon\Resource $resource
     *
     * @return mixed
     */
    public function delete(User $user, Resource $resource)
    {
        return $user->isAdmin();
    }
}
