<?php

namespace Tests\Feature\Bookings;

use Hydrofon\Booking;
use Hydrofon\Checkin;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckinTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Booking can be checked in.
     *
     * @return void
     */
    public function testBookingCanBeCheckedIn()
    {
        $user    = factory(User::class)->create();
        $booking = factory(Booking::class)->create();

        $this->actingAs($user)->post('checkins', [
            'booking_id' => $booking->id,
        ]);

        $this->assertNotNull($booking->checkin);
        $this->assertDatabaseHas('checkins', [
            'booking_id' => $booking->id,
            'user_id'    => $user->id,
        ]);
    }

    /**
     * A checkin can be deleted.
     *
     * @return void
     */
    public function testCheckinCanBeUndone()
    {
        $user     = factory(User::class)->create();
        $checkout = factory(Checkin::class)->create();

        $this->actingAs($user)->delete('checkouts/' . $checkout->id);

        $this->assertDatabaseMissing('checkouts', [
            'booking_id' => $checkout->booking_id,
            'user_id'    => $checkout->user_id,
        ]);
    }
}
