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
     */
    public function testAdminsCanViewABooking(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $booking = Booking::factory()->create();

        $this->assertTrue($admin->can('view', $booking));
        $this->assertFalse($user->can('view', $booking));
    }

    /**
     * The user that owns the booking can view it.
     */
    public function testOwnerCanViewBooking(): void
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertTrue($user->can('view', $booking));
    }

    /**
     * All users can create bookings.
     */
    public function testAllUsersCanCreateBookings(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->assertTrue($admin->can('create', Booking::class));
        $this->assertTrue($user->can('create', Booking::class));
    }

    /**
     * Administrators can update any booking.
     */
    public function testAdminsCanUpdateAnyBooking(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $booking = Booking::factory()->create();

        $this->assertTrue($admin->can('update', $booking));
        $this->assertFalse($user->can('update', $booking));
    }

    /**
     * The user that owns the booking can update it if it hasn't started or been checked out.
     */
    public function testOwnerCanUpdateBooking(): void
    {
        $user = User::factory()->create();
        $pastBooking = Booking::factory()->past()->create(['user_id' => $user->id]);
        $futureBooking = Booking::factory()->future()->create(['user_id' => $user->id]);
        $currentBooking = Booking::factory()->current()->create(['user_id' => $user->id]);
        $checkedOut = Booking::factory()->current()->checkedout()->createQuietly(['user_id' => $user->id]);

        $this->assertFalse($user->can('update', $pastBooking));
        $this->assertFalse($user->can('update', $currentBooking));
        $this->assertTrue($user->can('update', $futureBooking));
        $this->assertFalse($user->can('update', $checkedOut));
    }

    /**
     * Administrators can delete any booking.
     */
    public function testAdminsCanDeleteAnyBooking(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $booking = Booking::factory()->create();

        $this->assertTrue($admin->can('delete', $booking));
        $this->assertFalse($user->can('delete', $booking));
    }

    /**
     * The user that owns the booking can update it if it hasn't started or been checked out.
     */
    public function testOwnerCanDeleteBooking(): void
    {
        $user = User::factory()->create();
        $pastBooking = Booking::factory()->past()->create(['user_id' => $user->id]);
        $futureBooking = Booking::factory()->future()->create(['user_id' => $user->id]);
        $currentBooking = Booking::factory()->current()->create(['user_id' => $user->id]);
        $checkedOut = Booking::factory()->current()->checkedout()->createQuietly(['user_id' => $user->id]);

        $this->assertFalse($user->can('update', $pastBooking));
        $this->assertFalse($user->can('update', $currentBooking));
        $this->assertTrue($user->can('update', $futureBooking));
        $this->assertFalse($user->can('update', $checkedOut));
    }

    /**
     * Only administrators can create checkins.
     */
    public function testOnlyAdminUsersCanCreateCheckins(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->assertTrue($admin->can('checkinAny', Booking::class));
        $this->assertFalse($user->can('checkinAny', Booking::class));
    }

    /**
     * Only administrators can create checkouts.
     */
    public function testOnlyAdminUsersCanCreateCheckouts(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->assertTrue($admin->can('checkoutAny', Booking::class));
        $this->assertFalse($user->can('checkoutAny', Booking::class));
    }
}
