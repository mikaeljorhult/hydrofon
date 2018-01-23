<?php

namespace Tests\Feature\Groups;

use Hydrofon\Group;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Groups can be created and stored.
     *
     * @return void
     */
    public function testGroupsCanBeStored()
    {
        $admin = factory(User::class)->states('admin')->create();
        $group = factory(Group::class)->make();

        $response = $this->actingAs($admin)->post('groups', [
            'name' => $group->name,
        ]);

        $response->assertRedirect('/groups');
        $this->assertDatabaseHas('groups', [
            'name' => $group->name,
        ]);
    }

    /**
     * Groups must have a name.
     *
     * @return void
     */
    public function testGroupsMustHaveAName()
    {
        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)->post('groups', [
            'name' => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('name');
        $this->assertCount(0, Group::all());
    }

    /**
     * Non-admin users can not store groups.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotStoreGroups()
    {
        $user  = factory(User::class)->create();
        $group = factory(Group::class)->make();

        $response = $this->actingAs($user)->post('groups', [
            'name' => $group->name,
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('groups', [
            'name' => $group->name,
        ]);
    }
}
