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
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->delete('approvals/'.$approval->id);

        $response->assertRedirect();
        $this->assertModelMissing($approval);
    }

    /**
     * Approval can be revoked by approving user.
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
}
