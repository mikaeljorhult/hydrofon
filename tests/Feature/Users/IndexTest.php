<?php

namespace Tests\Feature\Users;

use Hydrofon\User;
use Hydrofon\Group;
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

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->get('users')
             ->assertSuccessful()
             ->assertSee(e($user->name));
    }

    /**
     * Users can be filtered by the name.
     *
     * @return void
     */
    public function testUsersAreFilteredByName()
    {
        $visibleUser = factory(User::class)->create();
        $notVisibleUser = factory(User::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->get('users?filter[name]='.$visibleUser->name)
             ->assertSuccessful()
             ->assertSee(route('users.edit', $visibleUser))
             ->assertDontSee(route('users.edit', $notVisibleUser));
    }

    /**
     * Users can be filtered by the e-mail address.
     *
     * @return void
     */
    public function testUsersAreFilteredByEmail()
    {
        $visibleUser = factory(User::class)->create();
        $notVisibleUser = factory(User::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->get('users?filter[email]='.$visibleUser->email)
             ->assertSuccessful()
             ->assertSee(route('users.edit', $visibleUser))
             ->assertDontSee(route('users.edit', $notVisibleUser));
    }

    /**
     * Users can be filtered by the group.
     *
     * @return void
     */
    public function testUsersAreFilteredByGroup()
    {
        $visibleUser = factory(User::class)->states('admin')->create();
        $notVisibleUser = factory(User::class)->create();

        $visibleUser->groups()->attach(factory(Group::class)->create());

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->get('users?filter[is_admin]=1')
             ->assertSuccessful()
             ->assertSee(route('users.edit', $visibleUser))
             ->assertDontSee(route('users.edit', $notVisibleUser));
    }
}
