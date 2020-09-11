<?php

namespace Tests\Feature\Groups;

use App\Group;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Posts request to persist a group.
     *
     * @param array               $overrides
     * @param \App\User|null $user
     *
     * @return \Illuminate\Testing\TestResponse
     */
    public function storeGroup($overrides = [], $user = null)
    {
        $group = Group::factory()->make($overrides);

        return $this->actingAs($user ?: User::factory()->admin()->create())
                    ->post('groups', $group->toArray());
    }

    /**
     * Groups can be created and stored.
     *
     * @return void
     */
    public function testGroupsCanBeStored()
    {
        $this->storeGroup([
            'name' => 'New Group',
        ])
             ->assertRedirect('/groups');

        $this->assertDatabaseHas('groups', [
            'name' => 'New Group',
        ]);
    }

    /**
     * Groups must have a name.
     *
     * @return void
     */
    public function testGroupsMustHaveAName()
    {
        $this->storeGroup(['name' => null])
             ->assertRedirect()
             ->assertSessionHasErrors('name');

        $this->assertCount(0, Group::all());
    }

    /**
     * Non-admin users can not store groups.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotStoreGroups()
    {
        $user = User::factory()->create();

        $this->storeGroup([], $user)
             ->assertStatus(403);

        $this->assertCount(0, Group::all());
    }
}
