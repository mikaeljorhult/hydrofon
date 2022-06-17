<?php

namespace Tests\Feature\Bookings;

use App\Models\Booking;
use App\Models\Checkout;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Booking can be checked out.
     *
     * @return void
     */
    public function testBookingCanBeCheckedOut()
    {
        $this->withoutExceptionHandling();

        $admin = User::factory()->admin()->create();
        $booking = Booking::withoutEvents(function() {
            return Booking::factory()->autoapproved()->create();
        });

        $this->actingAs($admin)->post('checkouts', [
            'booking_id' => $booking->id,
        ]);

        $this->assertTrue($booking->fresh()->isCheckedOut);
    }

    /**
     * Booking to be checked out must exist in database.
     *
     * @return void
     */
    public function testBookingMustExist()
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
     *
     * @return void
     */
    public function testNonAdminUsersCanNotCheckOutBookings()
    {
        $admin = User::factory()->create();
        $booking = Booking::withoutEvents(function() {
            return Booking::factory()->autoapproved()->create();
        });

        $response = $this->actingAs($admin)->post('checkouts', [
            'booking_id' => $booking->id,
        ]);

        $response->assertStatus(403);
        $this->assertFalse($booking->fresh()->isCheckedOut);
    }
}
