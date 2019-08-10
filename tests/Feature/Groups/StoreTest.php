<?php

namespace Tests\Feature\Groups;

use Hydrofon\User;
use Hydrofon\Group;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Posts request to persist a group.
     *
     * @param array               $overrides
     * @param \Hydrofon\User|null $user
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function storeGroup($overrides = [], $user = null)
    {
        $group = factory(Group::class)->make($overrides);

        return $this->actingAs($user ?: factory(User::class)->states('admin')->create())
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
        $user = factory(User::class)->create();

        $this->storeGroup([], $user)
             ->assertStatus(403);

        $this->assertCount(0, Group::all());
    }
}
