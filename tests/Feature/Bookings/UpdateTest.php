<?php

namespace Tests\Feature\Bookings;

use App\Models\Booking;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Edit route is available.
     */
    public function testEditRouteIsAvailable(): void
    {
        $booking = Booking::factory()->create();

        $this
            ->actingAs(User::factory()->admin()->create())
            ->get(route('bookings.edit', $booking))
            ->assertSuccessful()
            ->assertSee($booking->name);
    }

    /**
     * Bookings can be updated.
     */
    public function testBookingsCanBeUpdated(): void
    {
        $admin = User::factory()->admin()->create();
        $booking = Booking::factory()->create();
        $newResource = Resource::factory()->create();

        $response = $this->actingAs($admin)->put('bookings/'.$booking->id, [
            'resource_id' => $newResource->id,
            'start_time' => $booking->start_time,
            'end_time' => $booking->end_time,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'resource_id' => $newResource->id,
        ]);
    }

    /**
     * An administrator can change the user of a booking.
     */
    public function testAdministratorCanChangeUserOfBooking(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $booking = Booking::factory()->create();

        $response = $this->actingAs($admin)->put('bookings/'.$booking->id, [
            'user_id' => $user->id,
            'resource_id' => $booking->resource_id,
            'start_time' => $booking->start_time,
            'end_time' => $booking->end_time,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'user_id' => $user->id,
        ]);
    }

    /**
     * A regular user cannot change the user of a booking.
     */
    public function testUserCannotChangeUserOfABooking(): void
    {
        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();
        $booking = Booking::factory()->create();

        $response = $this->actingAs($firstUser)->put('bookings/'.$booking->id, [
            'user_id' => $secondUser->id,
            'resource_id' => $booking->resource_id,
            'start_time' => $booking->start_time,
            'end_time' => $booking->end_time,
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'user_id' => $booking->user_id,
        ]);
    }

    /**
     * A user can change a booking it owns.
     */
    public function testUserCanChangeBookingItOwns(): void
    {
        $booking = Booking::factory()->create();

        $response = $this->actingAs($booking->user)->put('bookings/'.$booking->id, [
            'resource_id' => $booking->resource_id,
            'start_time' => $booking->start_time->copy()->addHour(),
            'end_time' => $booking->end_time->copy()->addHour(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'start_time' => $booking->start_time->copy()->addHour(),
            'end_time' => $booking->end_time->copy()->addHour(),
        ]);
    }

    /**
     * A user can not change a booking it don't own.
     */
    public function testUserCanNotChangeBookingItDontOwn(): void
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->create();

        $response = $this->actingAs($user)->put('bookings/'.$booking->id, [
            'resource_id' => $booking->resource_id,
            'start_time' => $booking->start_time->copy()->addHour(),
            'end_time' => $booking->end_time->copy()->addHour(),
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'start_time' => $booking->start_time,
            'end_time' => $booking->end_time,
        ]);
    }

    /**
     * A user can not change a booking that has started.
     */
    public function testUserCanNotChangeBookingThatHasStarted(): void
    {
        $booking = Booking::factory()->past()->create();

        $response = $this->actingAs($booking->user)->put('bookings/'.$booking->id, [
            'resource_id' => $booking->resource_id,
            'start_time' => $booking->start_time->copy()->addHour(),
            'end_time' => $booking->end_time->copy()->addHour(),
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'start_time' => $booking->start_time,
            'end_time' => $booking->end_time,
        ]);
    }

    /**
     * A user can not change a booking that has started.
     */
    public function testUserCanNotChangeBookingThatHasBeenCheckedOut(): void
    {
        $booking = Booking::factory()->checkedout()->createQuietly();

        $response = $this->actingAs($booking->user)->put('bookings/'.$booking->id, [
            'resource_id' => $booking->resource_id,
            'start_time' => $booking->start_time->copy()->addHour(),
            'end_time' => $booking->end_time->copy()->addHour(),
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'start_time' => $booking->start_time,
            'end_time' => $booking->end_time,
        ]);
    }

    /**
     * Bookings must have an resource.
     */
    public function testBookingsMustHaveAResource(): void
    {
        $admin = User::factory()->admin()->create();
        $booking = Booking::factory()->create();

        $response = $this->actingAs($admin)->put('bookings/'.$booking->id, [
            'resource_id' => '',
            'start_time' => $booking->start_time,
            'end_time' => $booking->end_time,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('resource_id');
        $this->assertDatabaseHas('bookings', [
            'resource_id' => $booking->resource_id,
        ]);
    }

    /**
     * The requested resource must exist in the database.
     */
    public function testResourceMustExist(): void
    {
        $admin = User::factory()->admin()->create();
        $booking = Booking::factory()->create();

        $response = $this->actingAs($admin)->put('bookings/'.$booking->id, [
            'resource_id' => 100,
            'start_time' => $booking->start_time,
            'end_time' => $booking->end_time,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('resource_id');
        $this->assertDatabaseHas('bookings', [
            'resource_id' => $booking->resource_id,
        ]);
    }

    /**
     * Bookings can not overlap other bookings.
     */
    public function testBookingsCanNotOverlapOtherBookings(): void
    {
        $admin = User::factory()->admin()->create();
        $previous = Booking::factory()->create();
        $booking = Booking::factory()->create();

        $response = $this->actingAs($admin)->put('bookings/'.$booking->id, [
            'resource_id' => $previous->resource_id,
            'start_time' => $previous->start_time,
            'end_time' => $previous->end_time,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('resource_id');
        $this->assertDatabaseHas('bookings', [
            'resource_id' => $booking->resource_id,
            'start_time' => $booking->start_time,
            'end_time' => $booking->end_time,
        ]);
    }

    /**
     * A start time must be present.
     */
    public function testBookingsMustHaveAStartTime(): void
    {
        $admin = User::factory()->admin()->create();
        $booking = Booking::factory()->create();

        $response = $this->actingAs($admin)->put('bookings/'.$booking->id, [
            'resource_id' => $booking->resource_id,
            'start_time' => '',
            'end_time' => $booking->end_time,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('start_time');
        $this->assertDatabaseHas('bookings', [
            'start_time' => $booking->start_time,
        ]);
    }

    /**
     * Start time must be a valid timestamp.
     */
    public function testStartTimeMustBeValidTimestamp(): void
    {
        $admin = User::factory()->admin()->create();
        $booking = Booking::factory()->create();

        $response = $this->actingAs($admin)->put('bookings/'.$booking->id, [
            'resource_id' => $booking->resource_id,
            'start_time' => 'not-valid-time',
            'end_time' => $booking->end_time,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('start_time');
        $this->assertDatabaseHas('bookings', [
            'start_time' => $booking->start_time,
        ]);
    }

    /**
     * A end time must be present.
     */
    public function testBookingsMustHaveAEndTime(): void
    {
        $admin = User::factory()->admin()->create();
        $booking = Booking::factory()->create();

        $response = $this->actingAs($admin)->put('bookings/'.$booking->id, [
            'resource_id' => $booking->resource_id,
            'start_time' => $booking->start_time,
            'end_time' => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('end_time');
        $this->assertDatabaseHas('bookings', [
            'end_time' => $booking->end_time,
        ]);
    }

    /**
     * End time must be a valid timestamp.
     */
    public function testEndTimeMustBeValidTimestamp(): void
    {
        $admin = User::factory()->admin()->create();
        $booking = Booking::factory()->create();

        $response = $this->actingAs($admin)->put('bookings/'.$booking->id, [
            'resource_id' => $booking->resource_id,
            'start_time' => $booking->start_time,
            'end_time' => 'not-valid-time',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('end_time');
        $this->assertDatabaseHas('bookings', [
            'end_time' => $booking->end_time,
        ]);
    }

    /**
     * Booking have to start before it ends.
     */
    public function testStartTimeMustBeBeforeEndTime(): void
    {
        $admin = User::factory()->admin()->create();
        $booking = Booking::factory()->create();

        $response = $this->actingAs($admin)->put('bookings/'.$booking->id, [
            'resource_id' => $booking->resource_id,
            'start_time' => $booking->start_time,
            'end_time' => $booking->start_time->copy()->subHour(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('end_time');
        $this->assertDatabaseHas('bookings', [
            'end_time' => $booking->end_time,
        ]);
    }
}
