<?php

namespace Tests\Feature\Bookings;

use Hydrofon\Booking;
use Hydrofon\Checkout;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Booking can be checked out.
     *
     * @return void
     */
    public function testBookingCanBeCheckedOut()
    {
        $user    = factory(User::class)->create();
        $booking = factory(Booking::class)->create();

        $this->actingAs($user)->post('checkouts', [
            'booking_id' => $booking->id,
        ]);

        $this->assertNotNull($booking->checkout);
        $this->assertDatabaseHas('checkouts', [
            'booking_id' => $booking->id,
            'user_id'    => $user->id,
        ]);
    }

    /**
     * A checkout can be deleted.
     *
     * @return void
     */
    public function testCheckoutCanBeUndone()
    {
        $user     = factory(User::class)->create();
        $checkout = factory(Checkout::class)->create();

        $this->actingAs($user)->delete('checkouts/' . $checkout->id);

        $this->assertDatabaseMissing('checkouts', [
            'booking_id' => $checkout->booking_id,
            'user_id'    => $checkout->user_id,
        ]);
    }
}
