<?php

namespace Tests\Browser\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UsersEditTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testUsersEditIsReachable(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/users')
                ->assertSeeLink($user->name)
                ->clickLink($user->name)
                ->assertPathIs('/users/'.$user->id.'/edit')
                ->assertSee('Edit user')
                ->logout();
        });
    }

    public function testItemCanBeEdited(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/users/'.$user->id.'/edit')
                ->assertSee('Edit user')
                ->type('name', 'New name')
                ->clickAndWaitForReload('@submitupdate')
                ->assertPathIs('/users')
                ->assertSee('New name')
                ->logout();
        });

        $this->assertDatabaseHas(User::class, [
            'id' => $user->id,
            'name' => 'New name',
        ]);
    }

    public function testEditCanBeCancelled(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/users/'.$user->id.'/edit')
                ->click('@submitcancel')
                ->assertPathIs('/users')
                ->logout();
        });
    }
}
