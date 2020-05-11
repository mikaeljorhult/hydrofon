<?php

namespace Tests\Unit\Policies;

use App\Booking;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingPolicyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Administrators can view a booking.
     *
     * @return void
     */
    public function testAdminsCanViewABooking()
    {
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $booking = factory(Booking::class)->create();

        $this->assertTrue($admin->can('view', $booking));
        $this->assertFalse($user->can('view', $booking));
    }

    /**
     * The user that owns the booking can view it.
     *
     * @return void
     */
    public function testOwnerCanViewBooking()
    {
        $user = factory(User::class)->create();
        $booking = factory(Booking::class)->create([
            'user_id' => $user->id,
        ]);

        $this->assertTrue($user->can('view', $booking));
    }

    /**
     * All users can create bookings.
     *
     * @return void
     */
    public function testAllUsersCanCreateBookings()
    {
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $this->assertTrue($admin->can('create', Booking::class));
        $this->assertTrue($user->can('create', Booking::class));
    }

    /**
     * Administrators can update any booking.
     *
     * @return void
     */
    public function testAdminsCanUpdateAnyBooking()
    {
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $booking = factory(Booking::class)->create();

        $this->assertTrue($admin->can('update', $booking));
        $this->assertFalse($user->can('update', $booking));
    }

    /**
     * The user that owns the booking can update it if it hasn't started or been checked out.
     *
     * @return void
     */
    public function testOwnerCanUpdateBooking()
    {
        $user = factory(User::class)->create();
        $pastBooking = factory(Booking::class)->states('past')->create(['user_id' => $user->id]);
        $futureBooking = factory(Booking::class)->states('future')->create(['user_id' => $user->id]);
        $currentBooking = factory(Booking::class)->states('current')->create(['user_id' => $user->id]);
        $checkedOut = factory(Booking::class)->states('future')->create(['user_id' => $user->id]);

        $checkedOut->checkout()->create();

        $this->assertFalse($user->can('update', $pastBooking));
        $this->assertFalse($user->can('update', $currentBooking));
        $this->assertTrue($user->can('update', $futureBooking));
        $this->assertFalse($user->can('update', $checkedOut));
    }

    /**
     * Administrators can delete any booking.
     *
     * @return void
     */
    public function testAdminsCanDeleteAnyBooking()
    {
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $booking = factory(Booking::class)->create();

        $this->assertTrue($admin->can('delete', $booking));
        $this->assertFalse($user->can('delete', $booking));
    }

    /**
     * The user that owns the booking can update it if it hasn't started or been checked out.
     *
     * @return void
     */
    public function testOwnerCanDeleteBooking()
    {
        $user = factory(User::class)->create();
        $pastBooking = factory(Booking::class)->states('past')->create(['user_id' => $user->id]);
        $futureBooking = factory(Booking::class)->states('future')->create(['user_id' => $user->id]);
        $currentBooking = factory(Booking::class)->states('current')->create(['user_id' => $user->id]);
        $checkedOut = factory(Booking::class)->states('future')->create(['user_id' => $user->id]);

        $checkedOut->checkout()->create();

        $this->assertFalse($user->can('update', $pastBooking));
        $this->assertFalse($user->can('update', $currentBooking));
        $this->assertTrue($user->can('update', $futureBooking));
        $this->assertFalse($user->can('update', $checkedOut));
    }
}
