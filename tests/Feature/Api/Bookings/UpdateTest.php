<?php

namespace Tests\Feature\Api\Bookings;

use Hydrofon\Booking;
use Hydrofon\Resource;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Bookings can be updated.
     *
     * @return void
     */
    public function testBookingsCanBeUpdated()
    {
        $admin       = factory(User::class)->states('admin')->create();
        $booking     = factory(Booking::class)->create();
        $newResource = factory(Resource::class)->create();

        $response = $this->actingAs($admin)->put('api/bookings/'.$booking->id, [
            'resource_id' => $newResource->id,
            'start_time'  => $booking->start_time,
            'end_time'    => $booking->end_time,
        ], ['ACCEPT' => 'application/json']);

        $response->assertStatus(202)
                 ->assertJsonStructure([
                     'id',
                     'user',
                     'resource',
                     'created_by',
                     'start',
                     'end',
                 ])
                 ->assertJsonFragment([
                     'id'         => $booking->id,
                     'user'       => $booking->user_id,
                     'resource'   => $newResource->id,
                     'created_by' => $booking->created_by_id,
                     'start'      => (int) $booking->start_time->format('U'),
                     'end'        => (int) $booking->end_time->format('U'),
                 ]);
        $this->assertDatabaseHas('bookings', [
            'resource_id' => $newResource->id,
        ]);
    }

    /**
     * Bookings can be updated.
     *
     * @return void
     */
    public function testBookingsCanBeUpdatedWithApiFieldNames()
    {
        $admin       = factory(User::class)->states('admin')->create();
        $booking     = factory(Booking::class)->create();
        $newResource = factory(Resource::class)->create();

        $response = $this->actingAs($admin)->put('api/bookings/'.$booking->id, [
            'resource' => $newResource->id,
            'start'    => (int) $booking->start_time->format('U'),
            'end'      => (int) $booking->end_time->format('U'),
        ], ['ACCEPT' => 'application/json']);

        $response->assertStatus(202);
        $this->assertDatabaseHas('bookings', [
            'resource_id' => $newResource->id,
        ]);
    }

    /**
     * An administrator can change the user of a booking.
     *
     * @return void
     */
    public function testAdministratorCanChangeUserOfBooking()
    {
        $admin   = factory(User::class)->states('admin')->create();
        $user    = factory(User::class)->create();
        $booking = factory(Booking::class)->create();

        $response = $this->actingAs($admin)->put('api/bookings/'.$booking->id, [
            'user_id'     => $user->id,
            'resource_id' => $booking->resource_id,
            'start_time'  => $booking->start_time,
            'end_time'    => $booking->end_time,
        ], ['ACCEPT' => 'application/json']);

        $response->assertStatus(202);
        $this->assertDatabaseHas('bookings', [
            'id'      => $booking->id,
            'user_id' => $user->id,
        ]);
    }

    /**
     * A regular user cannot change the user of a booking.
     *
     * @return void
     */
    public function testUserCannotChangeUserOfABooking()
    {
        $firstUser  = factory(User::class)->create();
        $secondUser = factory(User::class)->create();
        $booking    = factory(Booking::class)->create();

        $response = $this->actingAs($firstUser)->put('api/bookings/'.$booking->id, [
            'user_id'     => $secondUser->id,
            'resource_id' => $booking->resource_id,
            'start_time'  => $booking->start_time,
            'end_time'    => $booking->end_time,
        ], ['ACCEPT' => 'application/json']);

        $response->assertStatus(403);
        $this->assertDatabaseHas('bookings', [
            'id'      => $booking->id,
            'user_id' => $booking->user_id,
        ]);
    }

    /**
     * A user can change a booking it owns.
     *
     * @return void
     */
    public function testUserCanChangeBookingItOwns()
    {
        $booking = factory(Booking::class)->create();

        $response = $this->actingAs($booking->user)->put('api/bookings/'.$booking->id, [
            'resource_id' => $booking->resource_id,
            'start_time'  => $booking->start_time->copy()->addHour(),
            'end_time'    => $booking->end_time->copy()->addHour(),
        ], ['ACCEPT' => 'application/json']);

        $response->assertStatus(202);
        $this->assertDatabaseHas('bookings', [
            'id'         => $booking->id,
            'start_time' => $booking->start_time->copy()->addHour(),
            'end_time'   => $booking->end_time->copy()->addHour(),
        ]);
    }

    /**
     * A user can not change a booking it don't own.
     *
     * @return void
     */
    public function testUserCanNotChangeBookingItDontOwn()
    {
        $user    = factory(User::class)->create();
        $booking = factory(Booking::class)->create();

        $response = $this->actingAs($user)->put('api/bookings/'.$booking->id, [
            'resource_id' => $booking->resource_id,
            'start_time'  => $booking->start_time->copy()->addHour(),
            'end_time'    => $booking->end_time->copy()->addHour(),
        ], ['ACCEPT' => 'application/json']);

        $response->assertStatus(403);
        $this->assertDatabaseHas('bookings', [
            'id'         => $booking->id,
            'start_time' => $booking->start_time,
            'end_time'   => $booking->end_time,
        ]);
    }
}
