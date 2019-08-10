<?php

namespace Tests\Feature\Api\Users;

use Hydrofon\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Users are listed in index.
     *
     * @return void
     */
    public function testUsersAreListed()
    {
        $user = factory(User::class)->create();

        $this->actingAs(factory(User::class)->create())
             ->getJson('api/users')
             ->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                     '*' => [
                         'id',
                         'name',
                         'email',
                     ],
                 ],
             ])
             ->assertJsonFragment([
                 'id'    => $user->id,
                 'name'  => $user->name,
                 'email' => $user->email,
             ]);
    }

    /**
     * Users can be filtered by name.
     *
     * @return void
     */
    public function testUsersAreFilteredByName()
    {
        $excludedUser = factory(User::class)->create(['name' => 'Excluded User']);
        $includedUser = factory(User::class)->create(['name' => 'Included User']);

        $this->actingAs(factory(User::class)->create())
             ->getJson('api/users?filter[name]=included')
             ->assertStatus(200)
             ->assertJsonFragment([
                 'id' => $includedUser->id,
             ])
             ->assertJsonMissing([
                 'id' => $excludedUser->id,
             ]);
    }

    /**
     * Users can be filtered by e-mail address.
     *
     * @return void
     */
    public function testUsersAreFilteredByEmail()
    {
        $excludedUser = factory(User::class)->create(['email' => 'excluded@hydrofon.se']);
        $includedUser = factory(User::class)->create(['email' => 'included@hydrofon.se']);

        $this->actingAs(factory(User::class)->create())
             ->getJson('api/users?filter[email]=included')
             ->assertStatus(200)
             ->assertJsonFragment([
                 'id' => $includedUser->id,
             ])
             ->assertJsonMissing([
                 'id' => $excludedUser->id,
             ]);
    }
}
