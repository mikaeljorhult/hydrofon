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
        $booking = Booking::factory()->create();

        $this->actingAs($admin)->post('checkins', [
            'booking_id' => $booking->id,
        ]);

        $this->assertNotNull($booking->checkin);
        $this->assertDatabaseHas('checkins', [
            'booking_id' => $booking->id,
            'user_id'    => $admin->id,
        ]);
    }

    /**
     * A checkin can be deleted.
     *
     * @return void
     */
    public function testCheckinCanBeUndone()
    {
        $admin = User::factory()->admin()->create();
        $checkin = Checkin::factory()->create();

        $this->actingAs($admin)->delete('checkins/'.$checkin->id);

        $this->assertDatabaseMissing('checkins', [
            'booking_id' => $checkin->booking_id,
            'user_id'    => $checkin->user_id,
        ]);
    }

    /**
     * End time is shortened if it has not ended yet when booking is checked in.
     *
     * @return void
     */
    public function testEndTimeIsShortenedWhenBookingIsCheckedIn()
    {
        $admin = User::factory()->admin()->create();
        $booking = Booking::factory()->create([
            'start_time' => now()->subHour(),
            'end_time'   => now()->addHour(5),
        ]);

        $this->actingAs($admin)->post('checkins', [
            'booking_id' => $booking->id,
        ]);

        $booking->refresh();
        $this->assertTrue($booking->end_time->lte(now()));
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
        $this->assertDatabaseMissing('checkins', [
            'booking_id' => 100,
            'user_id'    => $admin->id,
        ]);
    }

    /**
     * Non-admin users can not check in bookings.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotCheckInBookings()
    {
        $admin = User::factory()->create();
        $booking = Booking::factory()->create();

        $response = $this->actingAs($admin)->post('checkins', [
            'booking_id' => $booking->id,
        ]);

        $response->assertStatus(403);
        $this->assertNull($booking->checkin);
        $this->assertDatabaseMissing('checkins', [
            'booking_id' => $booking->id,
            'user_id'    => $admin->id,
        ]);
    }

    /**
     * Non-admin users can not delete checkins.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotDeleteCheckins()
    {
        $admin = User::factory()->create();
        $checkin = Checkin::factory()->create();

        $response = $this->actingAs($admin)->delete('checkins/'.$checkin->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('checkins', [
            'booking_id' => $checkin->booking_id,
            'user_id'    => $checkin->user_id,
        ]);
    }
}
