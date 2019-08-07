<?php

namespace Tests\Feature\Api\Bookings;

use Hydrofon\Booking;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Bookings can be created and stored.
     *
     * @return void
     */
    public function testBookingsCanBeStored()
    {
        $booking = factory(Booking::class)->make();

        $this->actingAs(factory(User::class)->create())
             ->postJson('api/bookings', $booking->toArray())
             ->assertStatus(201)
             ->assertJsonStructure([
                 'id',
                 'user',
                 'resource',
                 'created_by',
                 'start',
                 'end',
             ])
             ->assertJsonFragment([
                 'resource' => $booking->resource_id,
                 'start'    => (int) $booking->start_time->format('U'),
                 'end'      => (int) $booking->end_time->format('U'),
             ]);

        $this->assertDatabaseHas('bookings', [
            'user_id'    => 2,
            'start_time' => $booking->start_time,
            'end_time'   => $booking->end_time,
        ]);
    }

    /**
     * An administrator can create bookings for other users.
     *
     * @return void
     */
    public function testAdministratorCanCreateBookingForOtherUser()
    {
        $admin   = factory(User::class)->states('admin')->create();
        $user    = factory(User::class)->create();
        $booking = factory(Booking::class)->make([
            'user_id' => $user->id,
        ]);

        $this->actingAs($admin)
             ->postJson('api/bookings', $booking->toArray())
             ->assertStatus(201);

        $this->assertDatabaseHas('bookings', [
            'user_id'       => $user->id,
            'created_by_id' => $admin->id,
        ]);
    }

    /**
     * A regular user cannot create bookings for other users.
     *
     * @return void
     */
    public function testUserCannotCreateBookingsForOtherUser()
    {
        $firstUser  = factory(User::class)->create();
        $secondUser = factory(User::class)->create();
        $booking    = factory(Booking::class)->make([
            'user_id' => $secondUser->id,
        ]);

        $this->actingAs($firstUser)
             ->postJson('api/bookings', $booking->toArray())
             ->assertStatus(201);

        $this->assertDatabaseHas('bookings', [
            'user_id'       => $firstUser->id,
            'created_by_id' => $firstUser->id,
        ]);
    }

    /**
     * Bookings can be created using API field names.
     *
     * @return void
     */
    public function testBookingsCanBeStoredWithApiFieldNames()
    {
        $booking = factory(Booking::class)->make();

        $this->actingAs(factory(User::class)->create())
             ->postJson('api/bookings', [
                 'resource' => $booking->resource_id,
                 'start'    => (int) $booking->start_time->format('U'),
                 'end'      => (int) $booking->start_time->format('U'),
             ])
             ->assertStatus(422);

        $this->assertDatabaseMissing('bookings', [
            'start_time' => $booking->start_time,
            'end_time'   => $booking->end_time,
        ]);
    }

    /**
     * Bookings must have a resource.
     *
     * @return void
     */
    public function testBookingsMustHaveAResource()
    {
        $booking = factory(Booking::class)->make(['resource_id' => null]);

        $this->actingAs(factory(User::class)->create())
             ->postJson('api/bookings', $booking->toArray())
             ->assertStatus(422)
             ->assertJsonValidationErrors('resource_id');

        $this->assertCount(0, Booking::all());
    }
}
