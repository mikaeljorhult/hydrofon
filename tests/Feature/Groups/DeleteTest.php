<?php

namespace Tests\Feature\Groups;

use App\Models\Group;
use App\Models\User;
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
    public function testGroupsCanBeDeleted(): void
    {
        $admin = User::factory()->admin()->create();
        $group = Group::factory()->create();

        $response = $this->actingAs($admin)->delete('groups/'.$group->id);

        $response->assertRedirect('/groups');
        $this->assertModelMissing($group);
    }

    /**
     * Non-admin users can not delete groups.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotDeleteGroups(): void
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $response = $this->actingAs($user)->delete('groups/'.$group->id);

        $response->assertStatus(403);
        $this->assertModelExists($group);
    }
}
