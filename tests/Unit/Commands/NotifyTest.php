<?php

namespace Tests\Unit\Commands;

use App\Models\Booking;
use App\Models\Group;
use App\Models\User;
use App\Notifications\BookingAwaitingApproval;
use App\Notifications\BookingOverdue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
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

        Booking::factory()
               ->hasCheckout()
               ->for($user)
               ->create([
                   'start_time' => now()->subHours(2),
                   'end_time'   => now()->subMinute(),
               ]);

        $this->artisan('hydrofon:notifications');

        $this->assertCount(2, $user->notifications);
    }

    /**
     * Approver with bookings waiting for approval get notified.
     *
     * @return void
     */
    public function testApproverWithWaitingApprovalsGetNotified()
    {
        Config::set('hydrofon.require_approval', 'all');

        $approver = User::factory()->create();
        $group    = Group::factory()->hasAttached($approver, [], 'approvers')->create();

        Booking::factory()
               ->for(User::factory()->hasAttached($group))
               ->create();

        $this->artisan('hydrofon:notifications');

        $this->assertCount(1, $approver->notifications);
        $this->assertEquals(BookingAwaitingApproval::class, $approver->notifications->first()->type);
    }

    /**
     * Approver only get notified once about the same pending booking.
     *
     * @return void
     */
    public function testApproverDontGetNotifiedTwiceAboutSamePendingBooking()
    {
        Config::set('hydrofon.require_approval', 'all');

        $approver = User::factory()->create();
        $group    = Group::factory()->hasAttached($approver, [], 'approvers')->create();

        Booking::factory()
               ->for(User::factory()->hasAttached($group))
               ->create();

        $this->artisan('hydrofon:notifications');
        $this->artisan('hydrofon:notifications');

        $this->assertCount(1, $approver->notifications);
    }

    /**
     * Approver gets notified about new pending bookings.
     *
     * @return void
     */
    public function testApproverGetsNotifiedAboutNewBooking()
    {
        Config::set('hydrofon.require_approval', 'all');

        $approver = User::factory()->create();
        $approver->notify(new BookingAwaitingApproval());
        $approver->notifications()->update(['created_at' => now()->subHour()]);

        $group = Group::factory()->hasAttached($approver, [], 'approvers')->create();

        Booking::factory()
               ->for(User::factory()->hasAttached($group))
               ->create();

        $this->artisan('hydrofon:notifications');

        $this->assertCount(2, $approver->notifications);
    }
}
