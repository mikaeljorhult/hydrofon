<?php

namespace Tests\Browser\Users;

use Hydrofon\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class IndexTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * User can navigate to index page from home.
     *
     * @return void
     */
    public function testUserCanNavigateToIndexPage()
    {
        $user = factory(User::class)->states('admin')->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/calendar')
                    ->clickLink('Users')
                    ->assertPathIs('/users');
        });
    }

    /**
     * Available categories are displayed on index page.
     *
     * @return void
     */
    public function testUserIsListedOnIndexPage()
    {
        $admin = factory(User::class)->states('admin')->create();
        $otherUser = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($admin, $otherUser) {
            $browser->loginAs($admin)
                    ->visit('/users')
                    ->assertSee($otherUser->name);
        });
    }
}
