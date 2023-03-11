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
     * Edit route is available.
     *
     * @return void
     */
    public function testEditRouteIsAvailable(): void
    {
        $group = Group::factory()->create();

        $this
            ->actingAs(User::factory()->admin()->create())
            ->get(route('groups.edit', $group))
            ->assertSuccessful()
            ->assertSee($group->name);
    }

    /**
     * Groups can be updated.
     *
     * @return void
     */
    public function testGroupsCanBeUpdated(): void
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
    public function testGroupsMustHaveAName(): void
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
    public function testNonAdminUsersCanNotUpdateGroups(): void
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
