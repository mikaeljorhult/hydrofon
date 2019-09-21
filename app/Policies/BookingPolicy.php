<?php

namespace Hydrofon\Policies;

use Hydrofon\Booking;
use Hydrofon\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookingPolicy
{
    use HandlesAuthorization;

    /**
     * @param \Hydrofon\User $user
     * @param string         $ability
     *
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
     * @param \Hydrofon\User    $user
     * @param \Hydrofon\Booking $booking
     *
     * @return mixed
     */
    public function view(User $user, Booking $booking)
    {
        return $user->owns($booking);
    }

    /**
     * Determine whether the user can create bookings.
     *
     * @param \Hydrofon\User $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the booking.
     *
     * @param \Hydrofon\User    $user
     * @param \Hydrofon\Booking $booking
     *
     * @return mixed
     */
    public function update(User $user, Booking $booking)
    {
        return $user->owns($booking) && $booking->start_time->isFuture() && $booking->checkout === null;
    }

    /**
     * Determine whether the user can delete the booking.
     *
     * @param \Hydrofon\User    $user
     * @param \Hydrofon\Booking $booking
     *
     * @return mixed
     */
    public function delete(User $user, Booking $booking)
    {
        return $user->owns($booking) && $booking->start_time->isFuture() && $booking->checkout === null;
    }
}
