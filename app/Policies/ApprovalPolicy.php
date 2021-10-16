<?php

namespace App\Policies;

use App\Models\Approval;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApprovalPolicy
{
    use HandlesAuthorization;

    /**
     * @param  \App\Models\User  $user
     * @param  string  $ability
     * @return bool
     */
    public function before(User $user, $ability)
    {
        // Bail if approval isn't required.
        if (config('hydrofon.require_approval') === 'none') {
            return false;
        }
    }

    /**
     * Determine whether the user can see list of approvals.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function list(User $user)
    {
        return $user->isAdmin() || $user->approvingGroups()->exists();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Booking $booking)
    {
        if ($user->isAdmin()) {
            return true;
        }

        $booking->load(['user']);

        return $booking->user->groups()->whereIn('id', $user->approvingGroups()->pluck('id'))->exists();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Approval  $approval
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Approval $approval)
    {
        return $user->isAdmin() || $approval->user()->is($user);
    }
}
