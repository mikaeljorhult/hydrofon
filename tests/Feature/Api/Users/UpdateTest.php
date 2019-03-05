<?php

namespace Tests\Feature\Api\Users;

use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Users can be updated.
     *
     * @return void
     */
    public function testUsersCanBeUpdated()
    {
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($admin)->put('api/users/'.$user->id, [
            'name'  => 'Updated Name',
            'email' => $user->email,
        ], ['ACCEPT' => 'application/json']);

        $response->assertStatus(202)
                 ->assertJsonStructure([
                     'id',
                     'name',
                     'email',
                 ])
                 ->assertJsonFragment([
                     'id'    => $user->id,
                     'email' => $user->email,
                     'name'  => 'Updated Name',
                 ]);
        $this->assertDatabaseHas('users', [
            'name' => 'Updated Name',
        ]);
    }

    /**
     * A user can not update itself.
     *
     * @return void
     */
    public function testUserCanNotUpdateItself()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->put('api/users/'.$user->id, [
            'name'  => 'Updated Name',
            'email' => $user->email,
        ], ['ACCEPT' => 'application/json']);

        $response->assertStatus(403);
        $this->assertDatabaseHas('users', [
            'id'   => $user->id,
            'name' => $user->name,
        ]);
    }

    /**
     * A user can not update another user.
     *
     * @return void
     */
    public function testUserCanNotUpdateOtherUsers()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs(factory(User::class)->create())->put('api/users/'.$user->id, [
            'name'  => 'Updated Name',
            'email' => $user->email,
        ], ['ACCEPT' => 'application/json']);

        $response->assertStatus(403);
        $this->assertDatabaseHas('users', [
            'id'   => $user->id,
            'name' => $user->name,
        ]);
    }
}
