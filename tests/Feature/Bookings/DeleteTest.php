<?php

namespace Tests\Feature\Bookings;

use Hydrofon\Booking;
use Hydrofon\Checkin;
use Hydrofon\Checkout;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Bookings can be deleted.
     *
     * @return void
     */
    public function testBookingsCanBeDeleted()
    {
        $user    = factory(User::class)->create();
        $booking = factory(Booking::class)->create();

        $response = $this->actingAs($user)->delete('bookings/' . $booking->id);

        $response->assertRedirect('/bookings');
        $this->assertDatabaseMissing('bookings', [
            'name' => $booking->id,
        ]);
    }

    /**
     * Related checkin and checkout is deleted with booking.
     *
     * @return void
     */
    public function testRelatedCheckinAndCheckoutAreDeletedWithBooking()
    {
        $user    = factory(User::class)->create();
        $booking = factory(Booking::class)->create();

        $booking->checkin()->save(factory(Checkin::class)->create());
        $booking->checkout()->save(factory(Checkout::class)->create());

        $response = $this->actingAs($user)->delete('bookings/' . $booking->id);

        $response->assertRedirect('/bookings');
        $this->assertDatabaseMissing('checkins', [
            'booking_id' => $booking->id,
        ]);
        $this->assertDatabaseMissing('checkouts', [
            'booking_id' => $booking->id,
        ]);
    }
}
