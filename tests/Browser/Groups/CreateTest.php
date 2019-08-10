<?php

namespace Tests\Browser\Groups;

use Hydrofon\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * User can navigate to create page from groups index.
     *
     * @return void
     */
    public function testUserCanNavigateToCreatePage()
    {
        $admin = factory(User::class)->states('admin')->create();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/groups')
                    ->clickLink('New group')
                    ->assertPathIs('/groups/create')
                    ->assertSourceHas('form');
        });
    }
}
