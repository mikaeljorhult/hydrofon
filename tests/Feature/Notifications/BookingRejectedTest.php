<?php

namespace Tests\Feature\Notifications;

use App\Models\Booking;
use App\Models\Group;
use App\Models\User;
use App\Notifications\BookingRejected;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingRejectedTest extends TestCase
{
    use RefreshDatabase;

    /**
     * User with a rejected booking gets notified.
     *
     * @return void
     */
    public function testUserWithRejectedBookingGetNotified(): void
    {
        $this->approvalIsRequired();

        $approver = User::factory()->create();
        $group = Group::factory()->hasAttached($approver, [], 'approvers')->create();

        $booking = Booking::factory()->for(User::factory()->hasAttached($group))->create();

        $this->actingAs($approver);
        $booking->reject();

        $this->assertCount(1, $booking->user->notifications);
        $this->assertEquals(BookingRejected::class, $booking->user->notifications->first()->type);
    }

    /**
     * User with previous unread notification don't get notified again.
     *
     * @return void
     */
    public function testUserDontGetNotifiedMultipleTimes(): void
    {
        $this->approvalIsRequired();

        $approver = User::factory()->create();
        $group = Group::factory()->hasAttached($approver, [], 'approvers')->create();
        $user = User::factory()->hasAttached($group)->create();

        $bookings = Booking::factory()->count(2)->for($user)->create();

        $this->actingAs($approver);
        $bookings->each->reject();

        $this->assertCount(1, $user->notifications);
        $this->assertEquals(BookingRejected::class, $user->notifications->first()->type);
    }

    /**
     * Approver gets notified about new rejected bookings.
     *
     * @return void
     */
    public function testApproverGetsNotifiedAboutNewBooking(): void
    {
        $this->approvalIsRequired();

        $approver = User::factory()->create();
        $group = Group::factory()->hasAttached($approver, [], 'approvers')->create();

        $booking = Booking::factory()->for(User::factory()->hasAttached($group))->create();

        $booking->user->notify(new BookingRejected());
        $booking->user->notifications()->update(['created_at' => now()->subHour(), 'read_at' => now()]);

        $this->actingAs($approver);
        $booking->reject();

        $this->assertCount(2, $booking->user->notifications);
    }
}
