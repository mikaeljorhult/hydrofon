<?php

namespace Tests\Feature\Bookings;

use App\Booking;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Posts request to persist a booking.
     *
     * @param array               $overrides
     * @param \App\User|null $user
     *
     * @return \Illuminate\Testing\TestResponse
     */
    public function storeBooking($overrides = [], $user = null)
    {
        $booking = factory(Booking::class)->make($overrides);

        return $this->actingAs($user ?: factory(User::class)->create())
                    ->post('bookings', $booking->toArray());
    }

    /**
     * Bookings can be created and stored.
     *
     * @return void
     */
    public function testBookingsCanBeStored()
    {
        $this->storeBooking()
             ->assertRedirect('/');

        $this->assertDatabaseHas('bookings', [
            'user_id' => 2,
        ]);
    }

    /**
     * An administrator can create bookings for other users.
     *
     * @return void
     */
    public function testAdministratorCanCreateBookingForOtherUser()
    {
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $this->storeBooking(['user_id' => $user->id], $admin)
             ->assertRedirect('/');

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
        $firstUser = factory(User::class)->create();
        $secondUser = factory(User::class)->create();

        $this->storeBooking(['user_id' => $secondUser->id], $firstUser)
             ->assertRedirect('/');

        $this->assertDatabaseHas('bookings', [
            'user_id'       => $firstUser->id,
            'created_by_id' => $firstUser->id,
        ]);
    }

    /**
     * Bookings is owned by current user.
     *
     * @return void
     */
    public function testBookingsCanBeStoredWithoutUserID()
    {
        $this->storeBooking(['user_id' => null])
             ->assertRedirect('/');

        $this->assertDatabaseHas('bookings', [
            'user_id' => 2,
        ]);
    }

    /**
     * Bookings must have a resource.
     *
     * @return void
     */
    public function testBookingsMustHaveAResource()
    {
        $this->storeBooking(['resource_id' => null])
             ->assertRedirect()
             ->assertSessionHasErrors('resource_id');

        $this->assertCount(0, Booking::all());
    }

    /**
     * The requested resource must exist in the database.
     *
     * @return void
     */
    public function testResourceMustExist()
    {
        $this->storeBooking(['resource_id' => 100])
             ->assertRedirect()
             ->assertSessionHasErrors('resource_id');

        $this->assertCount(0, Booking::all());
    }

    /**
     * Bookings must have a start time.
     *
     * @return void
     */
    public function testBookingsMustHaveAStartTime()
    {
        $this->storeBooking(['start_time' => null])
             ->assertRedirect()
             ->assertSessionHasErrors('start_time');

        $this->assertCount(0, Booking::all());
    }

    /**
     * Start time must be a valid timestamp.
     *
     * @return void
     */
    public function testStartTimeMustBeValidTimestamp()
    {
        $user = factory(User::class)->create();
        $booking = factory(Booking::class)->make();

        $response = $this->actingAs($user)->post('bookings', [
            'resource_id' => $booking->resource_id,
            'start_time'  => 'not-valid-time',
            'end_time'    => $booking->end_time,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('start_time');

        $this->assertCount(0, Booking::all());
    }

    /**
     * Bookings must have a end time.
     *
     * @return void
     */
    public function testBookingsMustHaveAEndTime()
    {
        $this->storeBooking(['end_time' => null])
             ->assertRedirect()
             ->assertSessionHasErrors('end_time');

        $this->assertCount(0, Booking::all());
    }

    /**
     * End time must be a valid timestamp.
     *
     * @return void
     */
    public function testEndTimeMustBeValidTimestamp()
    {
        $user = factory(User::class)->create();
        $booking = factory(Booking::class)->make();

        $response = $this->actingAs($user)->post('bookings', [
            'resource_id' => $booking->resource_id,
            'start_time'  => $booking->start_time,
            'end_time'    => 'not-valid-time',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('end_time');

        $this->assertCount(0, Booking::all());
    }

    /**
     * Booking have to start before it ends.
     *
     * @return void
     */
    public function testStartTimeMustBeBeforeEndTime()
    {
        $this->storeBooking([
            'start_time' => now()->addMonth(),
            'end_time'   => now()->addMonth()->subHour(),
        ])
             ->assertRedirect()
             ->assertSessionHasErrors('start_time');

        $this->assertCount(0, Booking::all());
    }

    /**
     * Bookings can not overlap previous bookings.
     *
     * @return void
     */
    public function testBookingsCanNotOverlapPreviousBookings()
    {
        $booking = factory(Booking::class)->create();

        $this->storeBooking([
            'resource_id' => $booking->resource_id,
            'start_time'  => $booking->start_time,
            'end_time'    => $booking->end_time,
        ])
             ->assertRedirect()
             ->assertSessionHasErrors('resource_id');

        $this->assertCount(1, Booking::all());
    }
}
