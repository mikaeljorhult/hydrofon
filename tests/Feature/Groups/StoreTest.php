<?php

namespace Tests\Feature\Groups;

use Illuminate\Testing\TestResponse;
use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Posts request to persist a group.
     */
    public function storeGroup(array $overrides = [], ?User $user = null): TestResponse
    {
        $group = Group::factory()->make($overrides);

        return $this->actingAs($user ?: User::factory()->admin()->create())
                    ->post('groups', $group->toArray());
    }

    /**
     * Groups can be created and stored.
     */
    public function testGroupsCanBeStored(): void
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
     */
    public function testGroupsMustHaveAName(): void
    {
        $this->storeGroup(['name' => null])
             ->assertRedirect()
             ->assertSessionHasErrors('name');

        $this->assertCount(0, Group::all());
    }

    /**
     * Non-admin users can not store groups.
     */
    public function testNonAdminUsersCanNotStoreGroups(): void
    {
        $user = User::factory()->create();

        $this->storeGroup([], $user)
             ->assertStatus(403);

        $this->assertCount(0, Group::all());
    }
}
