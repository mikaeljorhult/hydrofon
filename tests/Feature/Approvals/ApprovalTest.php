<?php

namespace Tests\Feature\Approvals;

use App\Models\Booking;
use App\Models\Group;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApprovalTest extends TestCase
{
    use RefreshDatabase;

    /**
     * User not assigned as an approver can not access approvals index.
     */
    public function testNonApproverUserCannotSeeList(): void
    {
        $this->approvalIsRequired();

        $user = User::factory()->create();

        $this->actingAs($user)->get('approvals')
            ->assertForbidden();
    }

    /**
     * List is not available to approvers if approval requirement has been turned off.
     */
    public function testApproverCannotSeeListIfTurnedOff(): void
    {
        $this->approvalIsNotRequired();

        $approver = User::factory()->create();
        Group::factory()->hasAttached($approver, [], 'approvers')->create();

        $this->actingAs($approver)->get('approvals')
            ->assertForbidden();
    }

    /**
     * Bookings needing approval are listed.
     */
    public function testBookingsNeedingApprovalAreListed(): void
    {
        $this->approvalIsRequired();

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
     */
    public function testConfigCanRemoveNeedForApprovalForEquipment(): void
    {
        $this->approvalIsRequiredForFacilities();

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
     */
    public function testConfigCanRemoveNeedForApprovalForFacilities(): void
    {
        $this->approvalIsRequiredForEquipment();

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
     */
    public function testBookingsNotNeedingApprovalAreOmitted(): void
    {
        $this->approvalIsRequired();

        $approver = User::factory()->create();
        Group::factory()->hasAttached($approver, [], 'approvers')->create();

        $booking = Booking::factory()->autoapproved()->createQuietly();

        $this->actingAs($approver)->get('approvals')
            ->assertOk()
            ->assertDontSeeText($booking->user->name);
    }

    /**
     * Booking can be approved.
     */
    public function testBookingCanBeApproved(): void
    {
        $this->approvalIsRequired();

        $approver = User::factory()->create();
        $user = User::factory()->create();

        $group = Group::factory()->hasAttached($approver, [], 'approvers')->create();
        $user->groups()->attach($group);

        $booking = Booking::factory()
            ->for($user)
            ->create();

        $response = $this->actingAs($approver)->post('approvals', [
            'booking_id' => $booking->id,
        ]);

        $response->assertRedirect();
        $this->assertTrue($booking->fresh()->isApproved);
    }

    /**
     * Approval can be revoked by an administrator.
     */
    public function testApprovalCanBeRevokedByAdmin(): void
    {
        $this->approvalIsRequired();

        $admin = User::factory()->admin()->create();
        $booking = Booking::factory()->approved()->createQuietly();

        $response = $this->actingAs($admin)->delete('approvals/'.$booking->id);

        $response->assertRedirect();
        $this->assertFalse($booking->fresh()->isApproved);
    }

    /**
     * Users can not revoke an approval.
     */
    public function testUsersCanNotRevokeApproval(): void
    {
        $this->approvalIsRequired();

        $user = User::factory()->create();
        $booking = Booking::factory()->approved()->createQuietly();

        $response = $this->actingAs($user)->delete('approvals/'.$booking->id);

        $response->assertForbidden();
        $this->assertTrue($booking->fresh()->isApproved);
    }

    /**
     * If user is in an approving group and changes the booking after getting approval the approval is revoked.
     */
    public function testApprovalIsRevokedIfUserChangesBooking(): void
    {
        $this->approvalIsRequired();

        $booking = Booking::factory()->approved()->createQuietly();

        $group = Group::factory()->hasApprovers(1)->create();
        $booking->user->groups()->attach($group);

        $response = $this->actingAs($booking->user)->put('bookings/'.$booking->id, [
            'resource_id' => $booking->resource_id,
            'start_time' => $booking->start_time->addMinute(),
            'end_time' => $booking->end_time,
        ]);

        $response->assertRedirect();
        $this->assertTrue($booking->fresh()->isPending);
    }

    /**
     * If user don't need approval the booking is still automatically approved after changes.
     */
    public function testApprovalIsNotRevokedIfUserWithoutGroupsChangesBooking(): void
    {
        $this->approvalIsRequired();

        $booking = Booking::factory()->approved()->createQuietly();

        $response = $this->actingAs($booking->user)->put('bookings/'.$booking->id, [
            'resource_id' => $booking->resource_id,
            'start_time' => $booking->start_time->addMinute(),
            'end_time' => $booking->end_time,
        ]);

        $response->assertRedirect();
        $this->assertTrue($booking->fresh()->isApproved);
    }

    /**
     * Administrators can change a booking without approval being revoked.
     */
    public function testApprovalIsNotRevokedIfAdminChangesBooking(): void
    {
        $this->approvalIsRequired();

        $booking = Booking::factory()->approved()->createQuietly();

        $group = Group::factory()->hasApprovers(1)->create();
        $booking->user->groups()->attach($group);

        $response = $this->actingAs(User::factory()->admin()->create())->put('bookings/'.$booking->id, [
            'resource_id' => $booking->resource_id,
            'start_time' => $booking->start_time->addMinute(),
            'end_time' => $booking->end_time,
        ]);

        $response->assertRedirect();
        $this->assertTrue($booking->fresh()->isApproved);
    }
}
