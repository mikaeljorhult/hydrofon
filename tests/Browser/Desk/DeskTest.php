<?php

namespace Tests\Browser\Desk;

use Hydrofon\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

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
        $admin = factory(User::class)->states('admin')->create();

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
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

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
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();
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
