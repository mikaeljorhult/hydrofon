<?php

namespace Tests\Browser\Users;

use Hydrofon\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

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
                    ->visit('/home')
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
        $user      = factory(User::class)->create();
        $otherUser = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user, $otherUser) {
            $browser->loginAs($user)
                    ->visit('/users')
                    ->assertSee($otherUser->name);
        });
    }
}
