<?php

namespace Tests\Feature\Notifications;

use App\Models\Booking;
use App\Models\Group;
use App\Models\User;
use App\Notifications\BookingApproved;
use App\States\AutoApproved;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingApprovedTest extends TestCase
{
    use RefreshDatabase;

    /**
     * User with an approved booking gets notified.
     */
    public function testUserWithApprovedBookingGetNotified(): void
    {
        $this->approvalIsRequired();

        $approver = User::factory()->create();
        $group = Group::factory()->hasAttached($approver, [], 'approvers')->create();

        $booking = Booking::factory()->for(User::factory()->hasAttached($group))->create();

        $this->actingAs($approver);
        $booking->approve();

        $this->assertCount(1, $booking->user->notifications);
        $this->assertEquals(BookingApproved::class, $booking->user->notifications->first()->type);
    }

    /**
     * User with a (auto-)approved booking don't get notified.
     */
    public function testUserWithAutoApprovedBookingDontGetNotified(): void
    {
        $this->approvalIsRequired();

        $booking = Booking::factory()->create();

        $this->assertTrue($booking->state->equals(AutoApproved::class));
        $this->assertCount(0, $booking->user->notifications);
    }

    /**
     * User with previous unread notification don't get notified again.
     */
    public function testUserDontGetNotifiedMultipleTimes(): void
    {
        $this->approvalIsRequired();

        $approver = User::factory()->create();
        $group = Group::factory()->hasAttached($approver, [], 'approvers')->create();
        $user = User::factory()->hasAttached($group)->create();

        $bookings = Booking::factory()->count(2)->for($user)->create();

        $this->actingAs($approver);
        $bookings->each->approve();

        $this->assertCount(1, $user->notifications);
        $this->assertEquals(BookingApproved::class, $user->notifications->first()->type);
    }

    /**
     * Approver gets notified about new approved bookings.
     */
    public function testApproverGetsNotifiedAboutNewBooking(): void
    {
        $this->approvalIsRequired();

        $approver = User::factory()->create();
        $group = Group::factory()->hasAttached($approver, [], 'approvers')->create();

        $booking = Booking::factory()->for(User::factory()->hasAttached($group))->create();

        $booking->user->notify(new BookingApproved);
        $booking->user->notifications()->update(['created_at' => now()->subHour(), 'read_at' => now()]);

        $this->actingAs($approver);
        $booking->approve();

        $this->assertCount(2, $booking->user->notifications);
    }
}
