<?php

namespace Hydrofon\Policies;

use Hydrofon\User;
use Hydrofon\Identifier;
use Illuminate\Auth\Access\HandlesAuthorization;

class IdentifierPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the identifier.
     *
     * @param  \Hydrofon\User  $user
     * @param  \Hydrofon\Identifier  $identifier
     * @return mixed
     */
    public function view(User $user, Identifier $identifier)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create identifiers.
     *
     * @param  \Hydrofon\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the identifier.
     *
     * @param  \Hydrofon\User  $user
     * @param  \Hydrofon\Identifier  $identifier
     * @return mixed
     */
    public function update(User $user, Identifier $identifier)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the identifier.
     *
     * @param  \Hydrofon\User  $user
     * @param  \Hydrofon\Identifier  $identifier
     * @return mixed
     */
    public function delete(User $user, Identifier $identifier)
    {
        return $user->isAdmin();
    }
}
