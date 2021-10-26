<?php

namespace Tests\Unit\Commands;

use App\Models\Booking;
use App\Models\User;
use App\Notifications\BookingOverdue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotifyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * User with overdue a booking gets notified.
     *
     * @return void
     */
    public function testUserWithOverdueBookingGetNotified()
    {
        $user = User::factory()->create();
        Booking::factory()
               ->past()
               ->hasCheckout()
               ->for($user)
               ->create();

        $this->artisan('hydrofon:notifications');

        $this->assertCount(1, $user->notifications);
        $this->assertEquals(\App\Notifications\BookingOverdue::class, $user->notifications->first()->type);
    }

    /**
     * User only get notified once about the same booking.
     *
     * @return void
     */
    public function testUserDontGetNotifiedTwiceAboutSameBooking()
    {
        $user = User::factory()->create();
        $user->notify(new BookingOverdue());

        Booking::factory()
               ->past()
               ->hasCheckout()
               ->for($user)
               ->create();

        $this->artisan('hydrofon:notifications');

        $this->assertCount(1, $user->notifications);
    }

    /**
     * User gets notified about new bookings.
     *
     * @return void
     */
    public function testUserGetsNotifiedAboutNewBooking()
    {
        $user = User::factory()->create();
        $user->notify(new BookingOverdue());
        $user->notifications()->update(['created_at' => now()->subHour()]);

        $booking = Booking::factory()
               ->hasCheckout()
               ->for($user)
               ->create([
                   'start_time' => now()->subHours(2),
                   'end_time' => now()->subMinute(),
               ]);

        $this->artisan('hydrofon:notifications');

        $this->assertCount(2, $user->notifications);
    }
}
