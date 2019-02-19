<?php

namespace Tests\Feature\Api\Bookings;

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

        $this->actingAs(factory(User::class)->create())
             ->get('api/bookings', ['ACCEPT' => 'application/json'])
             ->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                     '*' => [
                         'id',
                         'user',
                         'resource',
                         'created_by',
                         'start',
                         'end',
                     ],
                 ],
             ])
             ->assertJsonFragment([
                 'id'         => $booking->id,
                 'user'       => $booking->user_id,
                 'resource'   => $booking->resource_id,
                 'created_by' => $booking->created_by_id,
                 'start'      => (int) $booking->start_time->format('U'),
                 'end'        => (int) $booking->end_time->format('U'),
             ]);
    }

    /**
     * Bookings can be filtered by time.
     *
     * @return void
     */
    public function testBookingsAreFilteredByDate()
    {
        $booking = factory(Booking::class)->create();

        $this->actingAs(factory(User::class)->create())
             ->get('api/bookings?filter[between]=100,200', ['ACCEPT' => 'application/json'])
             ->assertStatus(200)
             ->assertJsonMissing([
                 'id' => $booking->id,
             ]);
    }
}
