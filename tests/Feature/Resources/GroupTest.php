<?php

namespace Tests\Feature\Resources;

use App\Group;
use App\Resource;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Group relationships are stored when creating resource.
     *
     * @return void
     */
    public function testGroupRelationshipsAreStoredWhenCreatingResource()
    {
        $admin = User::factory()->admin()->create();
        $group = Group::factory()->create();
        $resource = Resource::factory()->make();

        $this->actingAs($admin)->post('resources', [
            'name'   => $resource->name,
            'groups' => [$group->id],
        ]);

        $this->assertDatabaseHas('group_resource', [
            'resource_id' => 1,
            'group_id'    => $group->id,
        ]);
    }

    /**
     * Non-existing groups cannot be added when creating resource.
     *
     * @return void
     */
    public function testNonExistingGroupsCannotBeAddedWhenStoringResource()
    {
        $admin = User::factory()->admin()->create();
        $resource = Resource::factory()->make();

        $response = $this->actingAs($admin)->post('resources', [
            'name'   => $resource->name,
            'groups' => [100],
        ]);

        $response->assertSessionHasErrors('groups.*');
        $this->assertDatabaseMissing('group_resource', [
            'group_id' => 100,
        ]);
    }

    /**
     * Group relationships are stored when updating resource.
     *
     * @return void
     */
    public function testGroupRelationshipsAreStoredWhenUpdatingResource()
    {
        $admin = User::factory()->admin()->create();
        $group = Group::factory()->create();
        $resource = Resource::factory()->create();

        $this->actingAs($admin)->put('resources/'.$resource->id, [
            'name'   => 'New Resource Name',
            'groups' => [$group->id],
        ]);

        $this->assertDatabaseHas('group_resource', [
            'resource_id' => $resource->id,
            'group_id'    => $group->id,
        ]);
    }

    /**
     * Group relationships are removed when updating user.
     *
     * @return void
     */
    public function testGroupRelationshipsAreRemovedWhenUpdatingResource()
    {
        $admin = User::factory()->admin()->create();
        $group = Group::factory()->create();
        $resource = Resource::factory()->create();

        $resource->groups()->attach($group);

        $this->assertDatabaseHas('group_resource', [
            'resource_id' => $resource->id,
            'group_id'    => $group->id,
        ]);

        $this->actingAs($admin)->put('resources/'.$resource->id, [
            'name'   => 'New Resource Name',
            'groups' => [],
        ]);

        $this->assertDatabaseMissing('group_resource', [
            'resource_id' => $resource->id,
            'group_id'    => $group->id,
        ]);
    }

    /**
     * Non-existing groups cannot be added when updating resource.
     *
     * @return void
     */
    public function testNonExistingGroupsCannotBeAddedWhenUpdatingResource()
    {
        $admin = User::factory()->admin()->create();
        $resource = Resource::factory()->create();

        $response = $this->actingAs($admin)->put('resources/'.$resource->id, [
            'name'   => 'New Resource Name',
            'groups' => [100],
        ]);

        $response->assertSessionHasErrors('groups.*');
        $this->assertDatabaseMissing('group_resource', [
            'group_id' => 100,
        ]);
    }
}
