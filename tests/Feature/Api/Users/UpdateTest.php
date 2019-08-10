<?php

namespace Tests\Feature\Api\Users;

use Hydrofon\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $user = factory(User::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->putJson('api/users/'.$user->id, [
                 'name'  => 'Updated Name',
                 'email' => $user->email,
             ])
             ->assertStatus(202)
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

        $this->actingAs($user)
             ->putJson('api/users/'.$user->id, [
                 'name'  => 'Updated Name',
                 'email' => $user->email,
             ])
             ->assertStatus(403);

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

        $this->actingAs(factory(User::class)->create())
             ->putJson('api/users/'.$user->id, [
                 'name'  => 'Updated Name',
                 'email' => $user->email,
             ])
             ->assertStatus(403);

        $this->assertDatabaseHas('users', [
            'id'   => $user->id,
            'name' => $user->name,
        ]);
    }
}
