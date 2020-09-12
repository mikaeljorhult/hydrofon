<?php

namespace Tests\Feature\Users;

use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Group relationships are stored when creating user.
     *
     * @return void
     */
    public function testGroupRelationshipsAreStoredWhenCreatingUser()
    {
        $admin = User::factory()->admin()->create();
        $group = Group::factory()->create();
        $user = User::factory()->make();

        $this->actingAs($admin)->post('users', [
            'email'                 => $user->email,
            'name'                  => $user->name,
            'password'              => 'password',
            'password_confirmation' => 'password',
            'groups'                => [$group->id],
        ]);

        $this->assertDatabaseHas('group_user', [
            'user_id'  => 2,
            'group_id' => $group->id,
        ]);
    }

    /**
     * Non-existing groups cannot be added when creating user.
     *
     * @return void
     */
    public function testNonExistingGroupsCannotBeAddedWhenStoringUser()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->make();

        $response = $this->actingAs($admin)->post('users', [
            'email'                 => $user->email,
            'name'                  => $user->name,
            'password'              => 'password',
            'password_confirmation' => 'password',
            'groups'                => [100],
        ]);

        $response->assertSessionHasErrors('groups.*');
        $this->assertDatabaseMissing('group_user', [
            'group_id' => 100,
        ]);
    }

    /**
     * Group relationships are stored when updating user.
     *
     * @return void
     */
    public function testGroupRelationshipsAreStoredWhenUpdatingUser()
    {
        $admin = User::factory()->admin()->create();
        $group = Group::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($admin)->put('users/'.$user->id, [
            'email'  => 'test@hydrofon.se',
            'name'   => $user->name,
            'groups' => [$group->id],
        ]);

        $this->assertDatabaseHas('group_user', [
            'user_id'  => $user->id,
            'group_id' => $group->id,
        ]);
    }

    /**
     * Group relationships are removed when updating user.
     *
     * @return void
     */
    public function testGroupRelationshipsAreRemovedWhenUpdatingUser()
    {
        $admin = User::factory()->admin()->create();
        $group = Group::factory()->create();
        $user = User::factory()->create();

        $user->groups()->attach($group);

        $this->assertDatabaseHas('group_user', [
            'user_id'  => $user->id,
            'group_id' => $group->id,
        ]);

        $this->actingAs($admin)->put('users/'.$user->id, [
            'email'  => 'test@hydrofon.se',
            'name'   => $user->name,
            'groups' => [],
        ]);

        $this->assertDatabaseMissing('group_user', [
            'user_id'  => $user->id,
            'group_id' => $group->id,
        ]);
    }

    /**
     * Non-existing groups cannot be added when updating user.
     *
     * @return void
     */
    public function testNonExistingGroupsCannotBeAddedWhenUpdatingUser()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->put('users/'.$user->id, [
            'email'  => 'test@hydrofon.se',
            'name'   => $user->name,
            'groups' => [100],
        ]);

        $response->assertSessionHasErrors('groups.*');
        $this->assertDatabaseMissing('group_user', [
            'group_id' => 100,
        ]);
    }
}
