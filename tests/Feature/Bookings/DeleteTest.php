<?php

namespace Tests\Feature\Bookings;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Bookings can be deleted.
     */
    public function testBookingsCanBeDeleted(): void
    {
        $admin = User::factory()->admin()->create();
        $booking = Booking::factory()->create();

        $response = $this->actingAs($admin)->delete('bookings/'.$booking->id);

        $response->assertRedirect();
        $this->assertDatabaseMissing('bookings', [
            'id' => $booking->id,
        ]);
    }

    /**
     * A user can delete a booking it owns.
     */
    public function testUserCanDeleteBookingItOwns(): void
    {
        $booking = Booking::factory()->create();

        $response = $this->actingAs($booking->user)->delete('bookings/'.$booking->id);

        $response->assertRedirect();
        $this->assertDatabaseMissing('bookings', [
            'id' => $booking->id,
        ]);
    }

    /**
     * A user can not delete a booking by another user.
     */
    public function testUserCanNotDeleteBookingByAnotherUser(): void
    {
        $booking = Booking::factory()->future()->create();
        $anotherUser = User::factory()->create();

        $response = $this->actingAs($anotherUser)->delete('bookings/'.$booking->id);

        $response->assertStatus(403);
        $this->assertModelExists($booking);
    }

    /**
     * A user can not delete a booking that has started.
     */
    public function testUserCanNotDeleteBookingThatHasStarted(): void
    {
        $booking = Booking::factory()->past()->create();

        $response = $this->actingAs($booking->user)->delete('bookings/'.$booking->id);

        $response->assertStatus(403);
        $this->assertModelExists($booking);
    }

    /**
     * A user can not delete a booking that has been checked out.
     */
    public function testUserCanNotDeleteBookingThatHasBeenCheckedOut(): void
    {
        $booking = Booking::factory()->past()->checkedout()->createQuietly();

        $response = $this->actingAs($booking->user)->delete('bookings/'.$booking->id);

        $response->assertStatus(403);
        $this->assertModelExists($booking);
    }
}
