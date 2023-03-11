<?php

namespace Tests\Feature\Groups;

use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApproverTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Approvers are stored when creating a group.
     */
    public function testApproversAreStoredWhenCreatingGroup(): void
    {
        $approver = User::factory()->create();

        $this->actingAs(User::factory()->admin()->create())->post('groups', [
            'name' => 'New Group',
            'approvers' => [$approver->id],
        ]);

        $this->assertDatabaseHas('approver_group', [
            'user_id' => $approver->id,
            'group_id' => 1,
        ]);
    }

    /**
     * Non-existing users cannot be added as an approver when creating a group.
     */
    public function testNonExistingUserCannotBeAddedWhenStoringGroup(): void
    {
        $response = $this->actingAs(User::factory()->admin()->create())->post('groups', [
            'name' => 'New Group',
            'approvers' => [100],
        ]);

        $response->assertSessionHasErrors('approvers.*');
        $this->assertDatabaseMissing('approver_group', [
            'user_id' => 100,
        ]);
    }

    /**
     * Approvers are stored when updating a group.
     */
    public function testApproversAreStoredWhenUpdatingGroup(): void
    {
        $group = Group::factory()->create();
        $approver = User::factory()->create();

        $this->actingAs(User::factory()->admin()->create())->put('groups/'.$group->id, [
            'name' => 'New Group',
            'approvers' => [$approver->id],
        ]);

        $this->assertDatabaseHas('approver_group', [
            'group_id' => $group->id,
            'user_id' => $approver->id,
        ]);
    }

    /**
     * Approvers are removed when updating a group.
     */
    public function testApproversAreRemovedWhenUpdatingGroup(): void
    {
        $group = Group::factory()->has(User::factory(), 'approvers')->create();

        $this->assertDatabaseHas('approver_group', [
            'group_id' => $group->id,
        ]);

        $this->actingAs(User::factory()->admin()->create())->put('groups/'.$group->id, [
            'name' => 'New Group',
            'approvers' => [],
        ]);

        $this->assertDatabaseCount('approver_group', 0);
    }

    /**
     * Non-existing user cannot be added as an approver when updating a group.
     */
    public function testNonExistingUserCannotBeAddedWhenUpdatingGroup(): void
    {
        $group = Group::factory()->create();

        $response = $this->actingAs(User::factory()->admin()->create())->put('groups/'.$group->id, [
            'name' => 'New Group',
            'approvers' => [100],
        ]);

        $response->assertSessionHasErrors('approvers.*');
        $this->assertDatabaseMissing('approver_group', [
            'group_id' => 100,
        ]);
    }
}
