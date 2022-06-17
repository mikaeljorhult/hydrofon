<?php

namespace Tests\Unit\Policies;

use App\Models\Booking;
use App\Models\User;
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
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $booking = Booking::factory()->create();

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
        $user = User::factory()->create();
        $booking = Booking::factory()->create([
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
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

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
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $booking = Booking::factory()->create();

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
        $user = User::factory()->create();
        $pastBooking = Booking::factory()->past()->create(['user_id' => $user->id]);
        $futureBooking = Booking::factory()->future()->create(['user_id' => $user->id]);
        $currentBooking = Booking::factory()->current()->create(['user_id' => $user->id]);
        $checkedOut = Booking::withoutEvents(function () use ($user) {
            return Booking::factory()->current()->checkedout()->create(['user_id' => $user->id]);
        });

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
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $booking = Booking::factory()->create();

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
        $user = User::factory()->create();
        $pastBooking = Booking::factory()->past()->create(['user_id' => $user->id]);
        $futureBooking = Booking::factory()->future()->create(['user_id' => $user->id]);
        $currentBooking = Booking::factory()->current()->create(['user_id' => $user->id]);
        $checkedOut = Booking::withoutEvents(function () use ($user) {
            return Booking::factory()->current()->checkedout()->create(['user_id' => $user->id]);
        });

        $this->assertFalse($user->can('update', $pastBooking));
        $this->assertFalse($user->can('update', $currentBooking));
        $this->assertTrue($user->can('update', $futureBooking));
        $this->assertFalse($user->can('update', $checkedOut));
    }

    /**
     * Only administrators can create checkins.
     *
     * @return void
     */
    public function testOnlyAdminUsersCanCreateCheckins()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->assertTrue($admin->can('checkinAny', Booking::class));
        $this->assertFalse($user->can('checkinAny', Booking::class));
    }

    /**
     * Only administrators can create checkouts.
     *
     * @return void
     */
    public function testOnlyAdminUsersCanCreateCheckouts()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->assertTrue($admin->can('checkoutAny', Booking::class));
        $this->assertFalse($user->can('checkoutAny', Booking::class));
    }
}
