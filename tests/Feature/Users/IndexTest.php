<?php

namespace Tests\Feature\Users;

use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->get('users')
             ->assertSuccessful()
             ->assertSee($user->name);
    }

    /**
     * Users index can be filtered by e-mail address.
     *
     * @return void
     */
    public function testUsersCanBeFilteredByEmail()
    {
        $visibleUser = factory(User::class)->create();
        $notVisibleUser = factory(User::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->get('users?'.http_build_query([
                     'filter' => $visibleUser->email,
                 ]))
             ->assertSuccessful()
             ->assertSee($visibleUser->email)
             ->assertDontSee($notVisibleUser->email);
    }

    /**
     * Users index can be filtered by name.
     *
     * @return void
     */
    public function testUsersCanBeFilteredByName()
    {
        $visibleUser = factory(User::class)->create();
        $notVisibleUser = factory(User::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->get('users?'.http_build_query([
                     'filter' => $visibleUser->name,
                 ]))
             ->assertSuccessful()
             ->assertSee($visibleUser->email)
             ->assertDontSee($notVisibleUser->email);
    }
}
