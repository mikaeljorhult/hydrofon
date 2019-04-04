<?php

namespace Tests\Feature\Groups;

use Hydrofon\Group;
use Hydrofon\User;
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
        $admin = factory(User::class)->states('admin')->create();
        $group = factory(Group::class)->create();

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
        $user  = factory(User::class)->create();
        $group = factory(Group::class)->create();

        $response = $this->actingAs($user)->delete('groups/'.$group->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('groups', [
            'id'   => $group->id,
            'name' => $group->name,
        ]);
    }
}
