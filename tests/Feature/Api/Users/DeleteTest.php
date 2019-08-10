<?php

namespace Tests\Feature\Api\Users;

use Hydrofon\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Users can be deleted.
     *
     * @return void
     */
    public function testUsersCanBeDeleted()
    {
        $user = factory(User::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->deleteJson('api/users/'.$user->id)
             ->assertStatus(204);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    /**
     * A user can not delete itself.
     *
     * @return void
     */
    public function testUserCanNotDeleteItself()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
             ->deleteJson('api/users/'.$user->id)
             ->assertStatus(403);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);
    }
}
