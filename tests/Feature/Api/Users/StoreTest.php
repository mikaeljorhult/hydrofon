<?php

namespace Tests\Feature\Api\Users;

use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Users can be created and stored.
     *
     * @return void
     */
    public function testUsersCanBeStored()
    {
        $user = factory(User::class)->make();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->postJson('api/users', array_merge(
                 $user->toArray(), [
                     'password'              => 'secret',
                     'password_confirmation' => 'secret',
                 ]
             ))
             ->assertStatus(201)
             ->assertJsonStructure([
                 'id',
                 'name',
                 'email',
             ])
             ->assertJsonFragment([
                 'name'  => $user->name,
                 'email' => $user->email,
             ]);

        $this->assertDatabaseHas('users', [
            'id'    => 2,
            'name'  => $user->name,
            'email' => $user->email,
        ]);
    }

    /**
     * Users can not create other users.
     *
     * @return void
     */
    public function testUsersCanNotCreateOtherUsers()
    {
        $user = factory(User::class)->make();

        $this->actingAs(factory(User::class)->create())
             ->postJson('api/users', array_merge(
                 $user->toArray(), [
                     'password'              => 'secret',
                     'password_confirmation' => 'secret',
                 ]
             ))
             ->assertStatus(403);

        $this->assertDatabaseMissing('users', [
            'name' => $user->name,
        ]);
    }

    /**
     * Users must have a name.
     *
     * @return void
     */
    public function testUsersMustHaveAName()
    {
        $user = factory(User::class)->make(['name' => null]);

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->postJson('api/users', $user->toArray())
             ->assertStatus(422)
             ->assertJsonValidationErrors('name');

        $this->assertDatabaseMissing('users', [
            'email' => $user->email,
        ]);
    }
}
