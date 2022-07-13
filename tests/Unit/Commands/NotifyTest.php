<?php

namespace Tests\Unit\Commands;

use App\Models\Booking;
use App\Models\Group;
use App\Models\Status;
use App\Models\User;
use App\Notifications\BookingApproved;
use App\Notifications\BookingAwaitingApproval;
use App\Notifications\BookingOverdue;
use App\Notifications\BookingRejected;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotifyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        $this->markTestIncomplete();
    }

    /**
     * Notify command will process all types of notifications.
     *
     * @return void
     */
    public function testCommandWillProcessAllNotificationTypes()
    {
        // Overdue booking.
        $overdueBooking = Booking::factory()
                                 ->past()
                                 ->hasCheckout()
                                 ->create();

        // Booking needing approval.
        $this->approvalIsRequired();
        $approver = User::factory()->create();
        $group = Group::factory()->hasAttached($approver, [], 'approvers')->create();

        Booking::factory()
               ->for(User::factory()->hasAttached($group))
               ->create();

        // Run command.
        $this->artisan('hydrofon:notifications');

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $overdueBooking->user->id,
            'type'         => BookingOverdue::class,
        ]);
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $approver->id,
            'type'         => BookingAwaitingApproval::class,
        ]);
    }

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

        $this->artisan('hydrofon:notifications', ['--type' => 'overdue']);

        $this->assertCount(1, $user->notifications);
        $this->assertEquals(BookingOverdue::class, $user->notifications->first()->type);
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

        $this->artisan('hydrofon:notifications', ['--type' => 'overdue']);

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
        $user->notifications()->update(['created_at' => now()->subHour(), 'read_at' => now()]);

        Booking::factory()
               ->hasCheckout()
               ->for($user)
               ->create([
                   'start_time' => now()->subHours(2),
                   'end_time'   => now()->subMinute(),
               ]);

        $this->artisan('hydrofon:notifications', ['--type' => 'overdue']);

        $this->assertCount(2, $user->notifications);
    }
}
