<?php

namespace Hydrofon\Policies;

use Hydrofon\User;
use Hydrofon\Checkout;
use Illuminate\Auth\Access\HandlesAuthorization;

class CheckoutPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create checkouts.
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
     * Determine whether the user can delete the checkout.
     *
     * @param \Hydrofon\User     $user
     * @param \Hydrofon\Checkout $checkout
     *
     * @return mixed
     */
    public function delete(User $user, Checkout $checkout)
    {
        return $user->isAdmin();
    }
}
