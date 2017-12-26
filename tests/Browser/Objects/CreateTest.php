<?php

namespace Tests\Browser\Objects;

use Hydrofon\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CreateTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * User can navigate to create page from objects index.
     *
     * @return void
     */
    public function testUserCanNavigateToCreatePage()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/objects')
                    ->clickLink('New object')
                    ->assertPathIs('/objects/create')
                    ->assertSourceHas('form');
        });
    }
}
