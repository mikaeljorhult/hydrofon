<?php

namespace Tests\Browser\Categories;

use Hydrofon\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CreateTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * User can navigate to create page from categories index.
     *
     * @return void
     */
    public function testUserCanNavigateToCreatePage()
    {
        $user = factory(User::class)->states('admin')->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/categories')
                    ->clickLink('New category')
                    ->assertPathIs('/categories/create')
                    ->assertSourceHas('form');
        });
    }
}
