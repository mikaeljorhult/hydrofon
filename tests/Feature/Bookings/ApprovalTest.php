<?php

namespace Tests\Feature\Bookings;

use App\Models\Approval;
use App\Models\Booking;
use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApprovalTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Bookings needing approval are listed.
     *
     * @return void
     */
    public function testBookingsNeedingApprovalAreListed()
    {
        $approver = User::factory()->create();

        $group = Group::factory()->hasAttached($approver, [], 'approvers')->create();
        $user = User::factory()->hasAttached($group)->create();

        $booking = Booking::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($approver)->get('approvals')
            ->assertOk()
            ->assertSeeText($booking->user->name);
    }

    /**
     * Bookings not needing approval are omitted from listing.
     *
     * @return void
     */
    public function testBookingsNotNeedingApprovalAreOmitted()
    {
        $approver = User::factory()->create();
        $booking = Booking::factory()->create();

        $this->actingAs($approver)->get('approvals')
             ->assertOk()
             ->assertDontSeeText($booking->user->name);
    }

    /**
     * Booking can be approved.
     *
     * @return void
     */
    public function testBookingCanBeApproved()
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->create();

        $group = Group::factory()->hasAttached($user, [], 'approvers')->create();
        $booking->user->groups()->attach($group);

        $response = $this->actingAs($user)->post('approvals', [
            'booking_id' => $booking->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('approvals', [
            'booking_id' => $booking->id,
            'user_id' => $user->id,
        ]);
    }

    /**
     * Booking can not be approved if user is in an approving group.
     *
     * @return void
     */
    public function testBookingCanNotBeApprovedIfUserIsInAnApprovingGroup()
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->create();

        $group = Group::factory()->hasApprovers(1)->create();
        $booking->user->groups()->attach($group);

        $response = $this->actingAs($user)->post('approvals', [
            'booking_id' => $booking->id,
        ]);

        $response->assertForbidden();
        $this->assertDatabaseCount('approvals', 0);
    }

    /**
     * Approval can be revoked by an administrator.
     *
     * @return void
     */
    public function testApprovalCanBeRevokedByAdmin()
    {
        $admin = User::factory()->admin()->create();
        $approval = Approval::factory()->create();

        $response = $this->actingAs($admin)->delete('approvals/'.$approval->id);

        $response->assertRedirect();
        $this->assertModelMissing($approval);
    }

    /**
     * Approval can be revoked by approving user.
     *
     * @return void
     */
    public function testApprovalCanBeRevokedByApprover()
    {
        $user = User::factory()->admin()->create();
        $approval = Approval::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->delete('approvals/'.$approval->id);

        $response->assertRedirect();
        $this->assertModelMissing($approval);
    }

    /**
     * Users can not revoke an approval.
     *
     * @return void
     */
    public function testUsersCanNotRevokeApproval()
    {
        $user = User::factory()->create();
        $approval = Approval::factory()->create();

        $response = $this->actingAs($user)->delete('approvals/'.$approval->id);

        $response->assertForbidden();
        $this->assertModelExists($approval);
    }

    /**
     * If user is in an approving group and changes the booking after getting approval the approval is revoked.
     *
     * @return void
     */
    public function testApprovalIsRevokedIfUserChangesBooking()
    {
        $approval = Approval::factory()->create();
        $booking = $approval->booking;

        $group = Group::factory()->hasApprovers(1)->create();
        $booking->user->groups()->attach($group);

        $response = $this->actingAs($booking->user)->put('bookings/'.$booking->id, [
            'resource_id' => $booking->resource_id,
            'start_time'  => $booking->start_time->addMinute(),
            'end_time'    => $booking->end_time,
        ]);

        $booking->refresh();

        $response->assertRedirect();
        $this->assertModelMissing($approval);
        $this->assertEquals('pending', $booking->status);
    }

    /**
     * If user don't need approval the booking is still automatically approved after changes.
     *
     * @return void
     */
    public function testApprovalIsNotRevokedIfUserWithoutGroupsChangesBooking()
    {
        $approval = Approval::factory()->create();
        $booking = $approval->booking;

        $response = $this->actingAs($booking->user)->put('bookings/'.$booking->id, [
            'resource_id' => $booking->resource_id,
            'start_time'  => $booking->start_time->addMinute(),
            'end_time'    => $booking->end_time,
        ]);

        $booking->refresh();

        $response->assertRedirect();
        $this->assertModelExists($approval);
        $this->assertEquals('approved', $booking->status);
    }

    /**
     * Administrators can change a booking without approval being revoked.
     *
     * @return void
     */
    public function testApprovalIsNotRevokedIfAdminChangesBooking()
    {
        $approval = Approval::factory()->create();
        $booking = $approval->booking;

        $group = Group::factory()->hasApprovers(1)->create();
        $booking->user->groups()->attach($group);

        $response = $this->actingAs(User::factory()->admin()->create())->put('bookings/'.$booking->id, [
            'resource_id' => $booking->resource_id,
            'start_time'  => $booking->start_time->addMinute(),
            'end_time'    => $booking->end_time,
        ]);

        $booking->refresh();

        $response->assertRedirect();
        $this->assertModelExists($approval);
        $this->assertEquals('approved', $booking->status);
    }
}
