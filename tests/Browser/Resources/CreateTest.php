<?php

namespace Tests\Browser\Resources;

use Hydrofon\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * User can navigate to create page from resources index.
     *
     * @return void
     */
    public function testUserCanNavigateToCreatePage()
    {
        $admin = factory(User::class)->states('admin')->create();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/resources')
                    ->clickLink('New resource')
                    ->assertPathIs('/resources/create')
                    ->assertSourceHas('form');
        });
    }
}
