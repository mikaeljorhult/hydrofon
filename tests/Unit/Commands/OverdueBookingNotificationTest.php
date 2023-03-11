<?php

namespace Tests\Unit\Commands;

use App\Models\Booking;
use App\Models\User;
use App\Notifications\BookingOverdue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OverdueBookingNotificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * User with overdue a booking gets notified.
     *
     * @return void
     */
    public function testUserWithOverdueBookingGetNotified(): void
    {
        $overdueBooking = Booking::factory()->overdue()->createQuietly();

        $this->artisan('hydrofon:overdue');

        $this->assertCount(1, $overdueBooking->user->notifications);
        $this->assertEquals(BookingOverdue::class, $overdueBooking->user->notifications->first()->type);
    }

    /**
     * User only get notified once about the same booking.
     *
     * @return void
     */
    public function testUserDontGetNotifiedTwiceAboutSameBooking(): void
    {
        $user = User::factory()->create();
        $user->notify(new BookingOverdue());

        Booking::factory()->overdue()->for($user)->createQuietly();

        $this->artisan('hydrofon:overdue');

        $this->assertCount(1, $user->notifications);
    }

    /**
     * User gets notified about new bookings.
     *
     * @return void
     */
    public function testUserGetsNotifiedAboutNewBooking(): void
    {
        $user = User::factory()->create();
        $user->notify(new BookingOverdue());
        $user->notifications()->update(['created_at' => now()->subHour(), 'read_at' => now()]);

        Booking::factory()->overdue()->for($user)->createQuietly();

        $this->artisan('hydrofon:overdue');

        $this->assertCount(2, $user->notifications);
    }
}
