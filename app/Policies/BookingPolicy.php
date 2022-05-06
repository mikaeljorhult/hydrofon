<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookingPolicy
{
    use HandlesAuthorization;

    /**
     * @param  \App\Models\User  $user
     * @param  string  $ability
     * @return bool
     */
    public function before(User $user, $ability)
    {
        // An administrator can do anything.
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the booking.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return mixed
     */
    public function view(User $user, Booking $booking)
    {
        return $user->owns($booking);
    }

    /**
     * Determine whether the user can create bookings.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the booking.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return mixed
     */
    public function update(User $user, Booking $booking)
    {
        return $user->owns($booking) && $booking->start_time->isFuture() && $booking->checkout === null;
    }

    /**
     * Determine whether the user can delete the booking.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return mixed
     */
    public function delete(User $user, Booking $booking)
    {
        return $user->owns($booking) && $booking->start_time->isFuture() && $booking->checkout === null;
    }

    /**
     * Determine whether the user can approve any bookings.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function approveAny(User $user)
    {
        return $user->approvingGroups()->exists();
    }

    /**
     * Determine whether the user can approve the booking.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return mixed
     */
    public function approve(User $user, Booking $booking)
    {
        $booking->loadMissing(['user']);

        return $booking->user->groups()->whereIn('id', $user->approvingGroups()->pluck('id'))->exists();
    }
}
