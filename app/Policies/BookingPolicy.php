<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookingPolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability): bool|null
    {
        // An administrator can do anything.
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view the booking.
     */
    public function view(User $user, Booking $booking): bool
    {
        return $user->owns($booking);
    }

    /**
     * Determine whether the user can create bookings.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the booking.
     */
    public function update(User $user, Booking $booking): bool
    {
        return $user->owns($booking) && $booking->start_time->isFuture() && ! $booking->isCheckedOut;
    }

    /**
     * Determine whether the user can delete the booking.
     */
    public function delete(User $user, Booking $booking): bool
    {
        return $user->owns($booking) && $booking->start_time->isFuture() && ! $booking->isCheckedOut;
    }

    /**
     * Determine whether the user can check in any bookings.
     *
     * @return mixed
     */
    public function checkinAny(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can check out any bookings.
     *
     * @return mixed
     */
    public function checkoutAny(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can approve any bookings.
     *
     * @return mixed
     */
    public function approveAny(User $user)
    {
        return $user->approvingGroups()->exists();
    }

    /**
     * Determine whether the user can approve the booking.
     *
     * @return mixed
     */
    public function approve(User $user, Booking $booking)
    {
        $booking->loadMissing(['user']);

        return $booking->user->groups()->whereIn('id', $user->approvingGroups()->pluck('id'))->exists();
    }
}
