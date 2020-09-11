<?php

namespace Tests\Browser\Categories;

use App\User;
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
        $user = User::factory()->admin()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/categories')
                    ->clickLink('New category')
                    ->assertPathIs('/categories/create')
                    ->assertSourceHas('form');
        });
    }
}
