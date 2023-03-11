<?php

namespace Tests\Feature\Bookings;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Booking can be checked out.
     */
    public function testBookingCanBeCheckedOut(): void
    {
        $this->withoutExceptionHandling();

        $admin = User::factory()->admin()->create();
        $booking = Booking::factory()->autoapproved()->createQuietly();

        $this->actingAs($admin)->post('checkouts', [
            'booking_id' => $booking->id,
        ]);

        $this->assertTrue($booking->fresh()->isCheckedOut);
    }

    /**
     * Booking to be checked out must exist in database.
     */
    public function testBookingMustExist(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post('checkouts', [
            'booking_id' => 100,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('booking_id');
    }

    /**
     * Non-admin users can not check out bookings.
     */
    public function testNonAdminUsersCanNotCheckOutBookings(): void
    {
        $admin = User::factory()->create();
        $booking = Booking::factory()->autoapproved()->createQuietly();

        $response = $this->actingAs($admin)->post('checkouts', [
            'booking_id' => $booking->id,
        ]);

        $response->assertStatus(403);
        $this->assertFalse($booking->fresh()->isCheckedOut);
    }
}
