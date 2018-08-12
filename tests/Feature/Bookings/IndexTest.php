<?php

namespace Tests\Feature\Bookings;

use Hydrofon\Booking;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Bookings are listed in index.
     *
     * @return void
     */
    public function testBookingsAreListed()
    {
        $booking = factory(Booking::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->get('bookings')
             ->assertSuccessful()
             ->assertSee($booking->resource->name);
    }
}
