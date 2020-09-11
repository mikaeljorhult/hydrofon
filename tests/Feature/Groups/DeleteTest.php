<?php

namespace Tests\Feature\Groups;

use App\Group;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Groups can be deleted.
     *
     * @return void
     */
    public function testGroupsCanBeDeleted()
    {
        $admin = User::factory()->admin()->create();
        $group = Group::factory()->create();

        $response = $this->actingAs($admin)->delete('groups/'.$group->id);

        $response->assertRedirect('/groups');
        $this->assertDatabaseMissing('groups', [
            'name' => $group->name,
        ]);
    }

    /**
     * Non-admin users can not delete groups.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotDeleteGroups()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $response = $this->actingAs($user)->delete('groups/'.$group->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('groups', [
            'id'   => $group->id,
            'name' => $group->name,
        ]);
    }
}
