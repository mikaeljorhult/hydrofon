<?php

namespace Tests\Unit\Commands;

use App\Models\Approval;
use App\Models\Booking;
use App\Models\Group;
use App\Models\User;
use App\Notifications\BookingApproved;
use App\Notifications\BookingAwaitingApproval;
use App\Notifications\BookingOverdue;
use App\Notifications\BookingRejected;
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

        $this->artisan('hydrofon:notifications', ['--type' => 'approval']);

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

        $this->artisan('hydrofon:notifications', ['--type' => 'approval']);
        $this->artisan('hydrofon:notifications', ['--type' => 'approval']);

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
        $approver->notifications()->update(['created_at' => now()->subHour(), 'read_at' => now()]);

        $group = Group::factory()->hasAttached($approver, [], 'approvers')->create();

        Booking::factory()
               ->for(User::factory()->hasAttached($group))
               ->create();

        $this->artisan('hydrofon:notifications', ['--type' => 'approval']);

        $this->assertCount(2, $approver->notifications);
    }

    /**
     * User with an approved booking gets notified.
     *
     * @return void
     */
    public function testUserWithApprovedBookingGetNotified()
    {
        Config::set('hydrofon.require_approval', 'all');

        $approval = Approval::factory()->create();

        $this->artisan('hydrofon:notifications', ['--type' => 'approved']);

        $this->assertCount(1, $approval->booking->user->notifications);
        $this->assertEquals(BookingApproved::class, $approval->booking->user->notifications->first()->type);
    }

    /**
     * User with a (auto-)approved booking don't get notified.
     *
     * @return void
     */
    public function testUserWithAutoApprovedBookingDontGetNotified()
    {
        Config::set('hydrofon.require_approval', 'all');

        $user = User::factory()
                    ->has(Booking::factory())
                    ->create();

        $this->artisan('hydrofon:notifications', ['--type' => 'approved']);

        $this->assertCount(0, $user->notifications);
    }

    /**
     * User only get notified once about the same approved booking.
     *
     * @return void
     */
    public function testUserDontGetNotifiedTwiceAboutSameApprovedBooking()
    {
        Config::set('hydrofon.require_approval', 'all');

        $approval = Approval::factory()->create();

        $this->artisan('hydrofon:notifications', ['--type' => 'approved']);
        $this->artisan('hydrofon:notifications', ['--type' => 'approved']);

        $this->assertCount(1, $approval->booking->user->notifications);
    }

    /**
     * User with a rejected booking gets notified.
     *
     * @return void
     */
    public function testUserWithRejectedBookingGetNotified()
    {
        Config::set('hydrofon.require_approval', 'all');

        $booking = Booking::factory()->create();
        $booking->setStatus('rejected');

        $this->artisan('hydrofon:notifications', ['--type' => 'rejected']);

        $this->assertCount(1, $booking->user->notifications);
        $this->assertEquals(BookingRejected::class, $booking->user->notifications->first()->type);
    }

    /**
     * User only get notified once about the same approved booking.
     *
     * @return void
     */
    public function testUserDontGetNotifiedTwiceAboutSameRejectedBooking()
    {
        Config::set('hydrofon.require_approval', 'all');

        $booking = Booking::factory()->create();
        $booking->setStatus('rejected');

        $this->artisan('hydrofon:notifications', ['--type' => 'rejected']);
        $this->artisan('hydrofon:notifications', ['--type' => 'rejected']);

        $this->assertCount(1, $booking->user->notifications);
    }
}
