<?php

namespace Hydrofon\Policies;

use Hydrofon\Object;
use Hydrofon\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ObjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can see the object in listings.
     *
     * @param \Hydrofon\User   $user
     * @param \Hydrofon\Object $object
     *
     * @return mixed
     */
    public function list(User $user, Object $object)
    {
        return $user->isAdmin() ||
               $object->groups->count() === 0 ||
               $user->groups->intersect($object->groups)->count() > 0;
    }

    /**
     * Determine whether the user can view the object.
     *
     * @param \Hydrofon\User   $user
     * @param \Hydrofon\Object $object
     *
     * @return mixed
     */
    public function view(User $user, Object $object)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create objects.
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
     * Determine whether the user can update the object.
     *
     * @param \Hydrofon\User   $user
     * @param \Hydrofon\Object $object
     *
     * @return mixed
     */
    public function update(User $user, Object $object)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the object.
     *
     * @param \Hydrofon\User   $user
     * @param \Hydrofon\Object $object
     *
     * @return mixed
     */
    public function delete(User $user, Object $object)
    {
        return $user->isAdmin();
    }
}
