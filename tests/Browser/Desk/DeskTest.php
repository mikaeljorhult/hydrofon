<?php

namespace Tests\Browser\Desk;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DeskTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * An admin user can navigate to the desk page.
     *
     * @return void
     */
    public function testAdminCanVisitDesk()
    {
        $admin = User::factory()->admin()->create();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/')
                    ->clickLink('Desk')
                    ->assertPathIs('/desk');
        });
    }

    /**
     * An admin user can search for a user.
     *
     * @return void
     */
    public function testAdminCanSearchForUser()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($admin, $user) {
            $browser->loginAs($admin)
                    ->visit('/desk')
                    ->keys('[name="search"]', $user->email, '{enter}')
                    ->assertPathIs('/desk/'.$user->email)
                    ->assertSee($user->name);
        });
    }

    /**
     * A user can be found by an identifier.
     *
     * @return void
     */
    public function testUserCanBeFoundByIdentifier()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $identifier = $user->identifiers()->create(['value' => 'test-identifier']);

        $this->browse(function (Browser $browser) use ($admin, $user, $identifier) {
            $browser->loginAs($admin)
                    ->visit('/desk')
                    ->keys('[name="search"]', $identifier->value, '{enter}')
                    ->assertPathIs('/desk/'.$identifier->value)
                    ->assertSee($user->name);
        });
    }
}
