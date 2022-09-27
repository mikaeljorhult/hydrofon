<?php

namespace Tests\Feature\Profile;

use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Profile bookings is available.
     *
     * @return void
     */
    public function testBookingsAreAvailable()
    {
        $booking = Booking::factory()->create();

        $this->actingAs($booking->user)
             ->get('profile/bookings')
             ->assertSuccessful()
             ->assertSee($booking->resource->name);
    }
}
