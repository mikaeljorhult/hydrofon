<?php

namespace Tests\Feature\Bookings;

use Hydrofon\Booking;
use Hydrofon\Checkin;
use Hydrofon\Checkout;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Bookings can be deleted.
     *
     * @return void
     */
    public function testBookingsCanBeDeleted()
    {
        $admin   = factory(User::class)->states('admin')->create();
        $booking = factory(Booking::class)->create();

        $response = $this->actingAs($admin)->delete('bookings/'.$booking->id);

        $response->assertRedirect('/bookings');
        $this->assertDatabaseMissing('bookings', [
            'id' => $booking->id,
        ]);
    }

    /**
     * A user can delete a booking it owns.
     *
     * @return void
     */
    public function testUserCanDeleteBookingItOwns()
    {
        $booking = factory(Booking::class)->create();

        $response = $this->actingAs($booking->user)->delete('bookings/'.$booking->id);

        $response->assertRedirect('/bookings');
        $this->assertDatabaseMissing('bookings', [
            'id' => $booking->id,
        ]);
    }

    /**
     * A user can not delete a booking by another user.
     *
     * @return void
     */
    public function testUserCanNotDeleteBookingByAnotherUser()
    {
        $booking     = factory(Booking::class)->states('future')->create();
        $anotherUser = factory(User::class)->create();

        $response = $this->actingAs($anotherUser)->delete('bookings/'.$booking->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
        ]);
    }

    /**
     * A user can not delete a booking that has started.
     *
     * @return void
     */
    public function testUserCanNotDeleteBookingThatHasStarted()
    {
        $booking = factory(Booking::class)->states('past')->create();

        $response = $this->actingAs($booking->user)->delete('bookings/'.$booking->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
        ]);
    }

    /**
     * A user can not delete a booking that has been checked out.
     *
     * @return void
     */
    public function testUserCanNotDeleteBookingThatHasBeenCheckedOut()
    {
        $booking = factory(Booking::class)->states('past')->create();
        factory(Checkout::class)->create([
            'booking_id' => $booking->id,
        ]);

        $response = $this->actingAs($booking->user)->delete('bookings/'.$booking->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
        ]);
    }

    /**
     * Related checkin and checkout is deleted with booking.
     *
     * @return void
     */
    public function testRelatedCheckinAndCheckoutAreDeletedWithBooking()
    {
        $admin   = factory(User::class)->states('admin')->create();
        $booking = factory(Booking::class)->create();

        $booking->checkin()->save(factory(Checkin::class)->create());
        $booking->checkout()->save(factory(Checkout::class)->create());

        $response = $this->actingAs($admin)->delete('bookings/'.$booking->id);

        $response->assertRedirect('/bookings');
        $this->assertDatabaseMissing('checkins', [
            'booking_id' => $booking->id,
        ]);
        $this->assertDatabaseMissing('checkouts', [
            'booking_id' => $booking->id,
        ]);
    }
}
