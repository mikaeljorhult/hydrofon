<?php

namespace Tests\Feature\Bookings;

use App\Booking;
use App\Checkin;
use App\Checkout;
use App\User;
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
        $admin = User::factory()->admin()->create();
        $booking = Booking::factory()->create();

        $response = $this->actingAs($admin)->delete('bookings/'.$booking->id);

        $response->assertRedirect('/');
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
        $booking = Booking::factory()->create();

        $response = $this->actingAs($booking->user)->delete('bookings/'.$booking->id);

        $response->assertRedirect('/');
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
        $booking = Booking::factory()->future()->create();
        $anotherUser = User::factory()->create();

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
        $booking = Booking::factory()->past()->create();

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
        $booking = Booking::factory()->past()->create();
        Checkout::factory()->create([
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
        $admin = User::factory()->admin()->create();
        $booking = Booking::factory()->create();

        $booking->checkin()->save(Checkin::factory()->create());
        $booking->checkout()->save(Checkout::factory()->create());

        $response = $this->actingAs($admin)->delete('bookings/'.$booking->id);

        $response->assertRedirect('/');
        $this->assertDatabaseMissing('checkins', [
            'booking_id' => $booking->id,
        ]);
        $this->assertDatabaseMissing('checkouts', [
            'booking_id' => $booking->id,
        ]);
    }
}
