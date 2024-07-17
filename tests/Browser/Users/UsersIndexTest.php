<?php

namespace Tests\Browser\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UsersIndexTest extends DuskTestCase
{
    use DatabaseTruncation;

    public function testIndexRouteIsReachable(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/calendar')
                ->assertSeeLink('Users')
                ->clickLink('Users')
                ->assertPathIs('/users')
                ->assertSee('Users')
                ->logout();
        });
    }

    public function testNameIsDisplayedInListing(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/users')
                ->assertSee($user->name)
                ->logout();
        });
    }

    public function testItemCanBeEditedInline(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/users')
                ->mouseover('@item-'.$user->id)
                ->click('@inline-edit-'.$user->id)
                ->waitFor('@inline-item-'.$user->id)
                ->type('name', 'New name')
                ->pause(500)
                ->press('Save')
                ->waitFor('@item-'.$user->id)
                ->assertSee('New name')
                ->logout();
        });

        $this->assertDatabaseHas(User::class, [
            'id' => $user->id,
            'name' => 'New name',
        ]);
    }

    public function testInlineEditingCanBeCancelled(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/users')
                ->mouseover('@item-'.$user->id)
                ->click('@inline-edit-'.$user->id)
                ->waitFor('@inline-item-'.$user->id)
                ->press('Cancel')
                ->waitFor('@item-'.$user->id)
                ->assertSee($user->name)
                ->logout();
        });
    }

    public function testItemCanBeDeleted(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/users')
                ->mouseover('@item-'.$user->id)
                ->click('@delete-'.$user->id)
                ->waitUntilMissing('@item-'.$user->id)
                ->assertDontSee($user->name)
                ->logout();
        });
    }

    public function testUsersCreateIsReachable(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/users')
                ->assertSeeLink('New user')
                ->clickLink('New user')
                ->assertPathIs('/users/create')
                ->assertSee('Create user')
                ->logout();
        });
    }

    public function testMultipleItemsCanBeDeleted(): void
    {
        $users = User::factory(5)->create();

        $this->browse(function (Browser $browser) use ($users) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/users')
                ->check('[name="selected[]"][value="'.$users->first()->id.'"]')
                ->check('[name="selected[]"][value="'.$users->last()->id.'"]')
                ->click('@delete-multiple')
                ->waitUntilMissing('@item-'.$users->first()->id)
                ->logout();
        });

        $this->assertDatabaseCount(User::class, 4);
        $this->assertModelMissing($users->first());
        $this->assertModelMissing($users->last());
    }
}
