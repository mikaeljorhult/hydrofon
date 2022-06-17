<?php

namespace Tests\Feature\Bookings;

use App\Models\Booking;
use App\Models\Checkin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckinTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Booking can be checked in.
     *
     * @return void
     */
    public function testBookingCanBeCheckedIn()
    {
        $admin = User::factory()->admin()->create();
        $booking = Booking::withoutEvents(function() {
            return Booking::factory()->checkedout()->create();
        });

        $this->actingAs($admin)->post('checkins', [
            'booking_id' => $booking->id,
        ]);

        $this->assertTrue($booking->fresh()->isCheckedIn);
    }

    /**
     * End time is shortened if it has not ended yet when booking is checked in.
     *
     * @return void
     */
    public function testEndTimeIsShortenedWhenBookingIsCheckedIn()
    {
        $admin = User::factory()->admin()->create();
        $booking = Booking::withoutEvents(function() {
            return Booking::factory()->checkedout()->create([
                'start_time' => now()->subHour(),
                'end_time'   => now()->addHour(5),
            ]);
        });

        $this->actingAs($admin)->post('checkins', [
            'booking_id' => $booking->id,
        ]);

        $this->assertTrue($booking->fresh()->end_time->lte(now()));
    }

    /**
     * Booking to be checked in must exist in database.
     *
     * @return void
     */
    public function testBookingMustExist()
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
     *
     * @return void
     */
    public function testNonAdminUsersCanNotCheckInBookings()
    {
        $admin = User::factory()->create();
        $booking = Booking::withoutEvents(function() {
            return Booking::factory()->checkedout()->create();
        });

        $response = $this->actingAs($admin)->post('checkins', [
            'booking_id' => $booking->id,
        ]);

        $response->assertStatus(403);
        $this->assertFalse($booking->fresh()->isCheckedIn);
    }
}
