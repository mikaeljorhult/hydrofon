<?php

namespace Tests\Feature\Bookings;

use Hydrofon\Booking;
use Hydrofon\Object;
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
        $user      = factory(User::class)->create();
        $booking   = factory(Booking::class)->create();
        $newObject = factory(Object::class)->create();

        $response = $this->actingAs($user)->put('bookings/' . $booking->id, [
            'object_id'  => $newObject->id,
            'start_time' => $booking->start_time,
            'end_time'   => $booking->end_time,
        ]);

        $response->assertRedirect('/bookings');
        $this->assertDatabaseHas('bookings', [
            'object_id' => $newObject->id,
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

        $response = $this->actingAs($admin)->put('bookings/' . $booking->id, [
            'user_id'    => $user->id,
            'object_id'  => $booking->object_id,
            'start_time' => $booking->start_time,
            'end_time'   => $booking->end_time,
        ]);

        $response->assertRedirect('/bookings');
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

        $response = $this->actingAs($firstUser)->put('bookings/' . $booking->id, [
            'user_id'    => $secondUser->id,
            'object_id'  => $booking->object_id,
            'start_time' => $booking->start_time,
            'end_time'   => $booking->end_time,
        ]);

        $response->assertRedirect('/bookings');
        $this->assertDatabaseHas('bookings', [
            'id'      => $booking->id,
            'user_id' => $booking->user_id,
        ]);
    }

    /**
     * Bookings must have an object.
     *
     * @return void
     */
    public function testBookingsMustHaveAnObject()
    {
        $user    = factory(User::class)->create();
        $booking = factory(Booking::class)->create();

        $response = $this->actingAs($user)->put('bookings/' . $booking->id, [
            'object_id'  => '',
            'start_time' => $booking->start_time,
            'end_time'   => $booking->end_time,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('object_id');
        $this->assertDatabaseHas('bookings', [
            'object_id' => $booking->object_id,
        ]);
    }

    /**
     * The requested object must exist in the database.
     *
     * @return void
     */
    public function testObjectMustExist()
    {
        $user    = factory(User::class)->create();
        $booking = factory(Booking::class)->create();

        $response = $this->actingAs($user)->put('bookings/' . $booking->id, [
            'object_id'  => 100,
            'start_time' => $booking->start_time,
            'end_time'   => $booking->end_time,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('object_id');
        $this->assertDatabaseHas('bookings', [
            'object_id' => $booking->object_id,
        ]);
    }

    /**
     * Bookings can not overlap other bookings.
     *
     * @return void
     */
    public function testBookingsCanNotOverlapOtherBookings()
    {
        $user     = factory(User::class)->create();
        $previous = factory(Booking::class)->create();
        $booking  = factory(Booking::class)->create();

        $response = $this->actingAs($user)->put('bookings/' . $booking->id, [
            'object_id'  => $previous->object_id,
            'start_time' => $previous->start_time,
            'end_time'   => $previous->end_time,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('object_id');
        $this->assertDatabaseHas('bookings', [
            'object_id'  => $booking->object_id,
            'start_time' => $booking->start_time,
            'end_time'   => $booking->end_time,
        ]);
    }

    /**
     * A start time must be present.
     *
     * @return void
     */
    public function testBookingsMustHaveAStartTime()
    {
        $user    = factory(User::class)->create();
        $booking = factory(Booking::class)->create();

        $response = $this->actingAs($user)->put('bookings/' . $booking->id, [
            'object_id'  => $booking->object_id,
            'start_time' => '',
            'end_time'   => $booking->end_time,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('start_time');
        $this->assertDatabaseHas('bookings', [
            'start_time' => $booking->start_time,
        ]);
    }

    /**
     * Start time must be a valid timestamp.
     *
     * @return void
     */
    public function testStartTimeMustBeValidTimestamp()
    {
        $user    = factory(User::class)->create();
        $booking = factory(Booking::class)->create();

        $response = $this->actingAs($user)->put('bookings/' . $booking->id, [
            'object_id'  => $booking->object_id,
            'start_time' => 'not-valid-time',
            'end_time'   => $booking->end_time,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('start_time');
        $this->assertDatabaseHas('bookings', [
            'start_time' => $booking->start_time,
        ]);
    }

    /**
     * A end time must be present.
     *
     * @return void
     */
    public function testBookingsMustHaveAEndTime()
    {
        $user    = factory(User::class)->create();
        $booking = factory(Booking::class)->create();

        $response = $this->actingAs($user)->put('bookings/' . $booking->id, [
            'object_id'  => $booking->object_id,
            'start_time' => $booking->start_time,
            'end_time'   => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('end_time');
        $this->assertDatabaseHas('bookings', [
            'end_time' => $booking->end_time,
        ]);
    }

    /**
     * End time must be a valid timestamp.
     *
     * @return void
     */
    public function testEndTimeMustBeValidTimestamp()
    {
        $user    = factory(User::class)->create();
        $booking = factory(Booking::class)->create();

        $response = $this->actingAs($user)->put('bookings/' . $booking->id, [
            'object_id'  => $booking->object_id,
            'start_time' => $booking->start_time,
            'end_time'   => 'not-valid-time',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('end_time');
        $this->assertDatabaseHas('bookings', [
            'end_time' => $booking->end_time,
        ]);
    }

    /**
     * Booking have to start before it ends.
     *
     * @return void
     */
    public function testStartTimeMustBeBeforeEndTime()
    {
        $user    = factory(User::class)->create();
        $booking = factory(Booking::class)->create();

        $response = $this->actingAs($user)->put('bookings/' . $booking->id, [
            'object_id'  => $booking->object_id,
            'start_time' => $booking->start_time,
            'end_time'   => $booking->start_time->copy()->subHour(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('end_time');
        $this->assertDatabaseHas('bookings', [
            'end_time' => $booking->end_time,
        ]);
    }
}
