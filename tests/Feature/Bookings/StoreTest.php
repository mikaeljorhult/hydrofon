<?php

namespace Tests\Feature\Bookings;

use Illuminate\Testing\TestResponse;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    private $storedBooking;

    /**
     * Posts request to persist a booking.
     */
    public function storeBooking(array $overrides = [], ?User $user = null): TestResponse
    {
        $this->storedBooking = Booking::factory()->make($overrides);

        return $this->actingAs($user ?: User::factory()->create())
                    ->post('bookings', $this->storedBooking->toArray());
    }

    /**
     * Bookings can be created and stored.
     */
    public function testBookingsCanBeStored(): void
    {
        $this->storeBooking()
             ->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'user_id' => $this->storedBooking->user_id + 1,
            'created_by_id' => $this->storedBooking->created_by_id + 1,
        ]);
    }

    /**
     * An administrator can create bookings for other users.
     */
    public function testAdministratorCanCreateBookingForOtherUser(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->storeBooking(['user_id' => $user->id], $admin)
             ->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'created_by_id' => $admin->id,
        ]);
    }

    /**
     * A regular user cannot create bookings for other users.
     */
    public function testUserCannotCreateBookingsForOtherUser(): void
    {
        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();

        $this->storeBooking(['user_id' => $secondUser->id], $firstUser)
             ->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'user_id' => $firstUser->id,
            'created_by_id' => $firstUser->id,
        ]);
    }

    /**
     * Bookings is owned by current user.
     */
    public function testBookingsCanBeStoredWithoutUserID(): void
    {
        $this->storeBooking(['user_id' => null])
             ->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'user_id' => 2,
        ]);
    }

    /**
     * Bookings must have a resource.
     */
    public function testBookingsMustHaveAResource(): void
    {
        $this->storeBooking(['resource_id' => null])
             ->assertRedirect()
             ->assertSessionHasErrors('resource_id');

        $this->assertCount(0, Booking::all());
    }

    /**
     * The requested resource must exist in the database.
     */
    public function testResourceMustExist(): void
    {
        $this->storeBooking(['resource_id' => 100])
             ->assertRedirect()
             ->assertSessionHasErrors('resource_id');

        $this->assertCount(0, Booking::all());
    }

    /**
     * Bookings must have a start time.
     */
    public function testBookingsMustHaveAStartTime(): void
    {
        $this->storeBooking(['start_time' => null])
             ->assertRedirect()
             ->assertSessionHasErrors('start_time');

        $this->assertCount(0, Booking::all());
    }

    /**
     * Start time must be a valid timestamp.
     */
    public function testStartTimeMustBeValidTimestamp(): void
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->make();

        $response = $this->actingAs($user)->post('bookings', [
            'resource_id' => $booking->resource_id,
            'start_time' => 'not-valid-time',
            'end_time' => $booking->end_time,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('start_time');

        $this->assertCount(0, Booking::all());
    }

    /**
     * Bookings must have a end time.
     */
    public function testBookingsMustHaveAEndTime(): void
    {
        $this->storeBooking(['end_time' => null])
             ->assertRedirect()
             ->assertSessionHasErrors('end_time');

        $this->assertCount(0, Booking::all());
    }

    /**
     * End time must be a valid timestamp.
     */
    public function testEndTimeMustBeValidTimestamp(): void
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->make();

        $response = $this->actingAs($user)->post('bookings', [
            'resource_id' => $booking->resource_id,
            'start_time' => $booking->start_time,
            'end_time' => 'not-valid-time',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('end_time');

        $this->assertCount(0, Booking::all());
    }

    /**
     * Booking have to start before it ends.
     */
    public function testStartTimeMustBeBeforeEndTime(): void
    {
        $this->storeBooking([
            'start_time' => now()->addMonth(),
            'end_time' => now()->addMonth()->subHour(),
        ])
             ->assertRedirect()
             ->assertSessionHasErrors('start_time');

        $this->assertCount(0, Booking::all());
    }

    /**
     * Bookings can not overlap previous bookings.
     */
    public function testBookingsCanNotOverlapPreviousBookings(): void
    {
        $booking = Booking::factory()->create();

        $this->storeBooking([
            'resource_id' => $booking->resource_id,
            'start_time' => $booking->start_time,
            'end_time' => $booking->end_time,
        ])
             ->assertRedirect()
             ->assertSessionHasErrors('resource_id');

        $this->assertCount(1, Booking::all());
    }
}
