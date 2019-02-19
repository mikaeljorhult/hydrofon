<?php

namespace Tests\Feature\Api\Bookings;

use Hydrofon\Booking;
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
        $admin = factory(User::class)->states('admin')->create();
        $booking = factory(Booking::class)->create();

        $response = $this->actingAs($admin)->delete('api/bookings/'.$booking->id, ['ACCEPT' => 'application/json']);

        $response->assertStatus(204);
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

        $response = $this->actingAs($booking->user)->delete('api/bookings/'.$booking->id,
            ['ACCEPT' => 'application/json']);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('bookings', [
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

        $response = $this->actingAs($booking->user)->delete('api/bookings/'.$booking->id,
            ['ACCEPT' => 'application/json']);

        $response->assertStatus(403);
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
        ]);
    }
}
