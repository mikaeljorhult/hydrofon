<?php

namespace Tests\Feature\Bookings;

use Hydrofon\Booking;
use Hydrofon\Checkin;
use Hydrofon\User;
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
        $user    = factory(User::class)->create();
        $booking = factory(Booking::class)->create();

        $this->actingAs($user)->post('checkins', [
            'booking_id' => $booking->id,
        ]);

        $this->assertNotNull($booking->checkin);
        $this->assertDatabaseHas('checkins', [
            'booking_id' => $booking->id,
            'user_id'    => $user->id,
        ]);
    }

    /**
     * A checkin can be deleted.
     *
     * @return void
     */
    public function testCheckinCanBeUndone()
    {
        $user    = factory(User::class)->create();
        $checkin = factory(Checkin::class)->create();

        $this->actingAs($user)->delete('checkins/' . $checkin->id);

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
        $user    = factory(User::class)->create();
        $booking = factory(Booking::class)->create([
            'start_time' => now()->subHour(),
            'end_time' => now()->addHour(5),
        ]);

        $this->actingAs($user)->post('checkins', [
            'booking_id' => $booking->id,
        ]);

        $booking->refresh();
        $this->assertTrue($booking->end_time->lte(now()));
    }
}
