<?php

namespace Tests\Feature\Bookings;

use Hydrofon\User;
use Tests\TestCase;
use Hydrofon\Booking;
use Hydrofon\Checkout;
use Hydrofon\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $admin = factory(User::class)->states('admin')->create();
        $booking = factory(Booking::class)->create();
        $newResource = factory(Resource::class)->create();

        $response = $this->actingAs($admin)->put('bookings/'.$booking->id, [
            'resource_id' => $newResource->id,
            'start_time'  => $booking->start_time,
            'end_time'    => $booking->end_time,
        ]);

        $response->assertRedirect('/');
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
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();
        $booking = factory(Booking::class)->create();

        $response = $this->actingAs($admin)->put('bookings/'.$booking->id, [
            'user_id'     => $user->id,
            'resource_id' => $booking->resource_id,
            'start_time'  => $booking->start_time,
            'end_time'    => $booking->end_time,
        ]);

        $response->assertRedirect('/');
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
        $firstUser = factory(User::class)->create();
        $secondUser = factory(User::class)->create();
        $booking = factory(Booking::class)->create();

        $response = $this->actingAs($firstUser)->put('bookings/'.$booking->id, [
            'user_id'     => $secondUser->id,
            'resource_id' => $booking->resource_id,
            'start_time'  => $booking->start_time,
            'end_time'    => $booking->end_time,
        ]);

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

        $response = $this->actingAs($booking->user)->put('bookings/'.$booking->id, [
            'resource_id' => $booking->resource_id,
            'start_time'  => $booking->start_time->copy()->addHour(),
            'end_time'    => $booking->end_time->copy()->addHour(),
        ]);

        $response->assertRedirect('/');
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
        $user = factory(User::class)->create();
        $booking = factory(Booking::class)->create();

        $response = $this->actingAs($user)->put('bookings/'.$booking->id, [
            'resource_id' => $booking->resource_id,
            'start_time'  => $booking->start_time->copy()->addHour(),
            'end_time'    => $booking->end_time->copy()->addHour(),
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('bookings', [
            'id'         => $booking->id,
            'start_time' => $booking->start_time,
            'end_time'   => $booking->end_time,
        ]);
    }

    /**
     * A user can not change a booking that has started.
     *
     * @return void
     */
    public function testUserCanNotChangeBookingThatHasStarted()
    {
        $booking = factory(Booking::class)->states('past')->create();

        $response = $this->actingAs($booking->user)->put('bookings/'.$booking->id, [
            'resource_id' => $booking->resource_id,
            'start_time'  => $booking->start_time->copy()->addHour(),
            'end_time'    => $booking->end_time->copy()->addHour(),
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('bookings', [
            'id'         => $booking->id,
            'start_time' => $booking->start_time,
            'end_time'   => $booking->end_time,
        ]);
    }

    /**
     * A user can not change a booking that has started.
     *
     * @return void
     */
    public function testUserCanNotChangeBookingThatHasBeenCheckedOut()
    {
        $booking = factory(Booking::class)->create();

        factory(Checkout::class)->create([
            'booking_id' => $booking->id,
        ]);

        $response = $this->actingAs($booking->user)->put('bookings/'.$booking->id, [
            'resource_id' => $booking->resource_id,
            'start_time'  => $booking->start_time->copy()->addHour(),
            'end_time'    => $booking->end_time->copy()->addHour(),
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('bookings', [
            'id'         => $booking->id,
            'start_time' => $booking->start_time,
            'end_time'   => $booking->end_time,
        ]);
    }

    /**
     * Bookings must have an resource.
     *
     * @return void
     */
    public function testBookingsMustHaveAResource()
    {
        $admin = factory(User::class)->states('admin')->create();
        $booking = factory(Booking::class)->create();

        $response = $this->actingAs($admin)->put('bookings/'.$booking->id, [
            'resource_id' => '',
            'start_time'  => $booking->start_time,
            'end_time'    => $booking->end_time,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('resource_id');
        $this->assertDatabaseHas('bookings', [
            'resource_id' => $booking->resource_id,
        ]);
    }

    /**
     * The requested resource must exist in the database.
     *
     * @return void
     */
    public function testResourceMustExist()
    {
        $admin = factory(User::class)->states('admin')->create();
        $booking = factory(Booking::class)->create();

        $response = $this->actingAs($admin)->put('bookings/'.$booking->id, [
            'resource_id' => 100,
            'start_time'  => $booking->start_time,
            'end_time'    => $booking->end_time,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('resource_id');
        $this->assertDatabaseHas('bookings', [
            'resource_id' => $booking->resource_id,
        ]);
    }

    /**
     * Bookings can not overlap other bookings.
     *
     * @return void
     */
    public function testBookingsCanNotOverlapOtherBookings()
    {
        $admin = factory(User::class)->states('admin')->create();
        $previous = factory(Booking::class)->create();
        $booking = factory(Booking::class)->create();

        $response = $this->actingAs($admin)->put('bookings/'.$booking->id, [
            'resource_id' => $previous->resource_id,
            'start_time'  => $previous->start_time,
            'end_time'    => $previous->end_time,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('resource_id');
        $this->assertDatabaseHas('bookings', [
            'resource_id' => $booking->resource_id,
            'start_time'  => $booking->start_time,
            'end_time'    => $booking->end_time,
        ]);
    }

    /**
     * A start time must be present.
     *
     * @return void
     */
    public function testBookingsMustHaveAStartTime()
    {
        $admin = factory(User::class)->states('admin')->create();
        $booking = factory(Booking::class)->create();

        $response = $this->actingAs($admin)->put('bookings/'.$booking->id, [
            'resource_id' => $booking->resource_id,
            'start_time'  => '',
            'end_time'    => $booking->end_time,
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
        $admin = factory(User::class)->states('admin')->create();
        $booking = factory(Booking::class)->create();

        $response = $this->actingAs($admin)->put('bookings/'.$booking->id, [
            'resource_id' => $booking->resource_id,
            'start_time'  => 'not-valid-time',
            'end_time'    => $booking->end_time,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('start_time');
        $this->assertDatabaseHas('bookings', [
            'start_time' => $booking->start_time,
        ]);
    }

    /**
     * Timestamps can be entered without seconds.
     *
     * @return void
     */
    public function testTimestampsCanBeEnteredWithoutSeconds()
    {
        $admin = factory(User::class)->states('admin')->create();
        $booking = factory(Booking::class)->create();
        $now = now();

        $response = $this->actingAs($admin)->put('bookings/'.$booking->id, [
            'resource_id' => $booking->resource_id,
            'start_time'  => $now->format('Y-m-d H:i'),
            'end_time'    => $now->copy()->addHour()->format('Y-m-d H:i'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'start_time' => $now->format('Y-m-d H:i:00'),
            'end_time'   => $now->copy()->addHour()->format('Y-m-d H:i:00'),
        ]);
    }

    /**
     * A end time must be present.
     *
     * @return void
     */
    public function testBookingsMustHaveAEndTime()
    {
        $admin = factory(User::class)->states('admin')->create();
        $booking = factory(Booking::class)->create();

        $response = $this->actingAs($admin)->put('bookings/'.$booking->id, [
            'resource_id' => $booking->resource_id,
            'start_time'  => $booking->start_time,
            'end_time'    => '',
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
        $admin = factory(User::class)->states('admin')->create();
        $booking = factory(Booking::class)->create();

        $response = $this->actingAs($admin)->put('bookings/'.$booking->id, [
            'resource_id' => $booking->resource_id,
            'start_time'  => $booking->start_time,
            'end_time'    => 'not-valid-time',
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
        $admin = factory(User::class)->states('admin')->create();
        $booking = factory(Booking::class)->create();

        $response = $this->actingAs($admin)->put('bookings/'.$booking->id, [
            'resource_id' => $booking->resource_id,
            'start_time'  => $booking->start_time,
            'end_time'    => $booking->start_time->copy()->subHour(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('end_time');
        $this->assertDatabaseHas('bookings', [
            'end_time' => $booking->end_time,
        ]);
    }
}
