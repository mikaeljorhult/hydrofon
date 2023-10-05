<?php

namespace Tests\Browser\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UsersCreateTest extends DuskTestCase
{
    use DatabaseMigrations;

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

    public function testItemCanBeCreated(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/users/create')
                ->assertSee('Create user')
                ->type('name', 'New user')
                ->type('email', 'test@hydrofon.se')
                ->type('password', 'password')
                ->type('password_confirmation', 'password')
                ->clickAndWaitForReload('@submitcreate')
                ->assertPathIs('/users')
                ->logout();
        });

        $this->assertDatabaseHas(User::class, [
            'name' => 'New user',
        ]);
    }

    public function testCreateCanBeCancelled(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/users/create')
                ->click('@submitcancel')
                ->assertPathIs('/users')
                ->logout();
        });
    }
}
