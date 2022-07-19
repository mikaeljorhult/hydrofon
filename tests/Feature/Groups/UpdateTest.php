<?php

namespace Tests\Feature\Groups;

use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Groups can be updated.
     *
     * @return void
     */
    public function testGroupsCanBeUpdated()
    {
        $admin = User::factory()->admin()->create();
        $group = Group::factory()->create();

        $response = $this->actingAs($admin)->put('groups/'.$group->id, [
            'name' => 'New Group Name',
        ]);

        $response->assertRedirect('/groups');
        $this->assertDatabaseHas('groups', [
            'name' => 'New Group Name',
        ]);
    }

    /**
     * Groups must have a name.
     *
     * @return void
     */
    public function testGroupsMustHaveAName()
    {
        $admin = User::factory()->admin()->create();
        $group = Group::factory()->create();

        $response = $this->actingAs($admin)->put('groups/'.$group->id, [
            'name' => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('name');
        $this->assertDatabaseHas('groups', [
            'name' => $group->name,
        ]);
    }

    /**
     * Non-admin users can not update groups.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotUpdateGroups()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $response = $this->actingAs($user)->put('groups/'.$group->id, [
            'name' => 'New Group Name',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('groups', [
            'id' => $group->id,
            'name' => $group->name,
        ]);
    }
}
