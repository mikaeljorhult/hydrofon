<?php

namespace App\Policies;

use App\Models\Checkout;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CheckoutPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create checkouts.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the checkout.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Checkout  $checkout
     * @return mixed
     */
    public function delete(User $user, Checkout $checkout)
    {
        return $user->isAdmin();
    }
}
