<?php

namespace Tests\Feature\Bookings;

use App\Booking;
use App\Checkout;
use App\User;
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
        $admin = User::factory()->admin()->create();
        $booking = Booking::factory()->create();

        $this->actingAs($admin)->post('checkouts', [
            'booking_id' => $booking->id,
        ]);

        $this->assertNotNull($booking->checkout);
        $this->assertDatabaseHas('checkouts', [
            'booking_id' => $booking->id,
            'user_id'    => $admin->id,
        ]);
    }

    /**
     * A checkout can be deleted.
     *
     * @return void
     */
    public function testCheckoutCanBeUndone()
    {
        $admin = User::factory()->admin()->create();
        $checkout = Checkout::factory()->create();

        $this->actingAs($admin)->delete('checkouts/'.$checkout->id);

        $this->assertDatabaseMissing('checkouts', [
            'booking_id' => $checkout->booking_id,
            'user_id'    => $checkout->user_id,
        ]);
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
        $this->assertDatabaseMissing('checkouts', [
            'booking_id' => 100,
            'user_id'    => $admin->id,
        ]);
    }

    /**
     * Non-admin users can not check out bookings.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotCheckOutBookings()
    {
        $admin = User::factory()->create();
        $booking = Booking::factory()->create();

        $response = $this->actingAs($admin)->post('checkouts', [
            'booking_id' => $booking->id,
        ]);

        $response->assertStatus(403);
        $this->assertNull($booking->checkout);
        $this->assertDatabaseMissing('checkouts', [
            'booking_id' => $booking->id,
            'user_id'    => $admin->id,
        ]);
    }

    /**
     * Non-admin users can not delete checkouts.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotDeleteCheckouts()
    {
        $user = User::factory()->create();
        $checkout = Checkout::factory()->create();

        $response = $this->actingAs($user)->delete('checkouts/'.$checkout->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('checkouts', [
            'booking_id' => $checkout->booking_id,
            'user_id'    => $checkout->user_id,
        ]);
    }
}
