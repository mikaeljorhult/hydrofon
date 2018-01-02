<?php

namespace Tests\Browser\Desk;

use Hydrofon\User;
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
                    ->type('search', $user->email)
                    ->press('Search')
                    ->assertPathIs('/desk/' . $user->email)
                    ->assertSee($user->name);
        });
    }
}
