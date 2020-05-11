<?php

namespace App\Policies;

use App\Identifier;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IdentifierPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the identifier.
     *
     * @param \App\User       $user
     * @param \App\Identifier $identifier
     *
     * @return mixed
     */
    public function view(User $user, Identifier $identifier)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create identifiers.
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
     * Determine whether the user can update the identifier.
     *
     * @param \App\User       $user
     * @param \App\Identifier $identifier
     *
     * @return mixed
     */
    public function update(User $user, Identifier $identifier)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the identifier.
     *
     * @param \App\User       $user
     * @param \App\Identifier $identifier
     *
     * @return mixed
     */
    public function delete(User $user, Identifier $identifier)
    {
        return $user->isAdmin();
    }
}
