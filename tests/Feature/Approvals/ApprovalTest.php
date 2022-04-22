<?php

namespace Tests\Feature\Approvals;

use App\Models\Approval;
use App\Models\Booking;
use App\Models\Group;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class ApprovalTest extends TestCase
{
    use RefreshDatabase;

    /**
     * User not assigned as an approver can not .
     *
     * @return void
     */
    public function testNonApproverUserCannotSeeList()
    {
        Config::set('hydrofon.require_approval', 'all');

        $user = User::factory()->create();

        $this->actingAs($user)->get('approvals')
             ->assertForbidden();
    }

    /**
     * List is not available to approvers if approval requirement has been turned off.
     *
     * @return void
     */
    public function testApproverCannotSeeListIfTurnedOff()
    {
        Config::set('hydrofon.require_approval', 'none');

        $approver = User::factory()->create();
        Group::factory()->hasAttached($approver, [], 'approvers')->create();

        $this->actingAs($approver)->get('approvals')
             ->assertForbidden();
    }

    /**
     * Bookings needing approval are listed.
     *
     * @return void
     */
    public function testBookingsNeedingApprovalAreListed()
    {
        Config::set('hydrofon.require_approval', 'all');

        $approver = User::factory()->create();

        $group = Group::factory()->hasAttached($approver, [], 'approvers')->create();

        $booking = Booking::factory()
                          ->for(User::factory()->hasAttached($group))
                          ->create();

        $this->actingAs($approver)->get('approvals')
            ->assertOk()
            ->assertSeeText($booking->user->name);
    }

    /**
     * Config may remove need for approval for equipment.
     *
     * @return void
     */
    public function testConfigCanRemoveNeedForApprovalForEquipment()
    {
        Config::set('hydrofon.require_approval', 'facilities');

        $approver = User::factory()->create();
        $group = Group::factory()->hasAttached($approver, [], 'approvers')->create();

        $equipmentBooking = Booking::factory()
                          ->for(User::factory()->hasAttached($group))
                          ->for(Resource::factory()->equipment())
                          ->create();

        $facilityBooking = Booking::factory()
                                  ->for(User::factory()->hasAttached($group))
                                  ->for(Resource::factory()->facility())
                                  ->create();

        $this->actingAs($approver)->get('approvals')
             ->assertOk()
             ->assertDontSeeText($equipmentBooking->user->name)
             ->assertSeeText($facilityBooking->user->name);
    }

    /**
     * Config may remove need for approval for facilities.
     *
     * @return void
     */
    public function testConfigCanRemoveNeedForApprovalForFacilities()
    {
        Config::set('hydrofon.require_approval', 'equipment');

        $approver = User::factory()->create();
        $group = Group::factory()->hasAttached($approver, [], 'approvers')->create();

        $equipmentBooking = Booking::factory()
                                   ->for(User::factory()->hasAttached($group))
                                   ->for(Resource::factory()->equipment())
                                   ->create();

        $facilityBooking = Booking::factory()
                                  ->for(User::factory()->hasAttached($group))
                                  ->for(Resource::factory()->facility())
                                  ->create();

        $this->actingAs($approver)->get('approvals')
             ->assertOk()
             ->assertSeeText($equipmentBooking->user->name)
             ->assertDontSeeText($facilityBooking->user->name);
    }

    /**
     * Bookings not needing approval are omitted from listing.
     *
     * @return void
     */
    public function testBookingsNotNeedingApprovalAreOmitted()
    {
        Config::set('hydrofon.require_approval', 'all');

        $approver = User::factory()->create();
        Group::factory()->hasAttached($approver, [], 'approvers')->create();

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
        Config::set('hydrofon.require_approval', 'all');

        $approver = User::factory()->create();
        $user     = User::factory()->create();

        $group = Group::factory()->hasAttached($approver, [], 'approvers')->create();
        $user->groups()->attach($group);

        $booking = Booking::factory()
                          ->for($user)
                          ->create();

        $response = $this->actingAs($approver)->post('approvals', [
            'booking_id' => $booking->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('approvals', [
            'booking_id' => $booking->id,
            'user_id' => $approver->id,
        ]);

        $status = $booking->latestStatus();
        $this->assertEquals('approved', $status->name);
        $this->assertEquals($approver->id, $status->created_by->id);
    }

    /**
     * Booking can not be approved if user is in an approving group.
     *
     * @return void
     */
    public function testBookingCanNotBeApprovedIfUserIsInAnApprovingGroup()
    {
        Config::set('hydrofon.require_approval', 'all');

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
        Config::set('hydrofon.require_approval', 'all');

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
        Config::set('hydrofon.require_approval', 'all');

        $user = User::factory()->admin()->create();
        $approval = Approval::factory()->for($user)->create();

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
        Config::set('hydrofon.require_approval', 'all');

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
        Config::set('hydrofon.require_approval', 'all');

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
        Config::set('hydrofon.require_approval', 'all');

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
        Config::set('hydrofon.require_approval', 'all');

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
