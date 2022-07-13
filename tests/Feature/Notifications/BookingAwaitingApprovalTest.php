<?php

namespace Tests\Feature\Notifications;

use App\Models\Booking;
use App\Models\Group;
use App\Models\User;
use App\Notifications\BookingAwaitingApproval;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingAwaitingApprovalTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Approver with bookings waiting for approval get notified.
     *
     * @return void
     */
    public function testApproverWithWaitingApprovalsGetNotified()
    {
        $this->approvalIsRequired();

        $approver = User::factory()->create();
        $group = Group::factory()->hasAttached($approver, [], 'approvers')->create();

        Booking::factory()
               ->for(User::factory()->hasAttached($group))
               ->create();

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
        $this->approvalIsRequired();

        $approver = User::factory()->create();
        $group = Group::factory()->hasAttached($approver, [], 'approvers')->create();

        Booking::factory()
               ->count(2)
               ->for(User::factory()->hasAttached($group))
               ->create();

        $this->assertCount(1, $approver->notifications);
    }

    /**
     * Approver gets notified about new pending bookings.
     *
     * @return void
     */
    public function testApproverGetsNotifiedAboutNewBooking()
    {
        $this->approvalIsRequired();

        $approver = User::factory()->create();
        $approver->notify(new BookingAwaitingApproval());
        $approver->notifications()->update(['created_at' => now()->subHour(), 'read_at' => now()]);

        $group = Group::factory()->hasAttached($approver, [], 'approvers')->create();

        Booking::factory()
               ->for(User::factory()->hasAttached($group))
               ->create();

        $this->assertCount(2, $approver->notifications);
    }
}
