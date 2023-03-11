<?php

namespace App\Policies;

use App\Models\Identifier;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IdentifierPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the identifier.
     */
    public function view(User $user, Identifier $identifier): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create identifiers.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the identifier.
     */
    public function update(User $user, Identifier $identifier): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the identifier.
     */
    public function delete(User $user, Identifier $identifier): bool
    {
        return $user->isAdmin();
    }
}
