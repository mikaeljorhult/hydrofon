<?php

namespace Tests\Feature\Api\Users;

use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($admin)->delete('api/users/'.$user->id, ['ACCEPT' => 'application/json']);

        $response->assertStatus(204);
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

        $response = $this->actingAs($user)->delete(
            'api/users/'.$user->id,
            ['ACCEPT' => 'application/json']
        );

        $response->assertStatus(403);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);
    }
}
