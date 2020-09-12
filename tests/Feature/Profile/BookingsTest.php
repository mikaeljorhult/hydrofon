<?php

namespace Tests\Feature\Profile;

use App\Models\Booking;
use App\Models\User;
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
        $user = User::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
             ->get('profile/bookings')
             ->assertSuccessful()
             ->assertSee($booking->resource->name);
    }
}
