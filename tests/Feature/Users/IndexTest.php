<?php

namespace Tests\Feature\Users;

use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Users are listed in index.
     */
    public function testUsersAreListed(): void
    {
        $user = User::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->get('users')
            ->assertSuccessful()
            ->assertSee($user->name);
    }

    /**
     * Users can be filtered by the name.
     */
    public function testUsersAreFilteredByName(): void
    {
        $visibleUser = User::factory()->create();
        $notVisibleUser = User::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->get('users?filter[name]='.$visibleUser->name)
            ->assertSuccessful()
            ->assertSee(route('users.edit', $visibleUser))
            ->assertDontSee(route('users.edit', $notVisibleUser));
    }

    /**
     * Users can be filtered by the e-mail address.
     */
    public function testUsersAreFilteredByEmail(): void
    {
        $visibleUser = User::factory()->create();
        $notVisibleUser = User::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->get('users?filter[email]='.$visibleUser->email)
            ->assertSuccessful()
            ->assertSee(route('users.edit', $visibleUser))
            ->assertDontSee(route('users.edit', $notVisibleUser));
    }

    /**
     * Users can be filtered by the group.
     */
    public function testUsersAreFilteredByGroup(): void
    {
        $visibleUser = User::factory()->admin()->create();
        $notVisibleUser = User::factory()->create();

        $visibleUser->groups()->attach(Group::factory()->create());

        $this->actingAs(User::factory()->admin()->create())
            ->get('users?filter[is_admin]=1')
            ->assertSuccessful()
            ->assertSee(route('users.edit', $visibleUser))
            ->assertDontSee(route('users.edit', $notVisibleUser));
    }
}
