<?php

namespace App\Policies;

use App\Checkout;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CheckoutPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create checkouts.
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
     * Determine whether the user can delete the checkout.
     *
     * @param \App\User     $user
     * @param \App\Checkout $checkout
     *
     * @return mixed
     */
    public function delete(User $user, Checkout $checkout)
    {
        return $user->isAdmin();
    }
}
