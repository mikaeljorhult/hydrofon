<?php

namespace Tests\Feature\Bookings;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckinTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Booking can be checked in.
     */
    public function testBookingCanBeCheckedIn(): void
    {
        $admin = User::factory()->admin()->create();
        $booking = Booking::factory()->checkedout()->createQuietly();

        $this->actingAs($admin)->post('checkins', [
            'booking_id' => $booking->id,
        ]);

        $this->assertTrue($booking->fresh()->isCheckedIn);
    }

    /**
     * End time is shortened if it has not ended yet when booking is checked in.
     */
    public function testEndTimeIsShortenedWhenBookingIsCheckedIn(): void
    {
        $admin = User::factory()->admin()->create();
        $booking = Booking::factory()->checkedout()->createQuietly([
            'start_time' => now()->subHour(),
            'end_time' => now()->addHour(5),
        ]);

        $this->actingAs($admin)->post('checkins', [
            'booking_id' => $booking->id,
        ]);

        $this->assertTrue($booking->fresh()->end_time->lte(now()));
    }

    /**
     * Booking to be checked in must exist in database.
     */
    public function testBookingMustExist(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post('checkins', [
            'booking_id' => 100,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('booking_id');
    }

    /**
     * Non-admin users can not check in bookings.
     */
    public function testNonAdminUsersCanNotCheckInBookings(): void
    {
        $admin = User::factory()->create();
        $booking = Booking::factory()->checkedout()->createQuietly();

        $response = $this->actingAs($admin)->post('checkins', [
            'booking_id' => $booking->id,
        ]);

        $response->assertStatus(403);
        $this->assertFalse($booking->fresh()->isCheckedIn);
    }
}
