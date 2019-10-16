<?php

namespace Tests\Browser\Users;

use Hydrofon\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CreateTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * User can navigate to create page from users index.
     *
     * @return void
     */
    public function testUserCanNavigateToCreatePage()
    {
        $user = factory(User::class)->states('admin')->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/users')
                    ->clickLink('New user')
                    ->assertPathIs('/users/create')
                    ->assertSourceHas('form');
        });
    }
}
