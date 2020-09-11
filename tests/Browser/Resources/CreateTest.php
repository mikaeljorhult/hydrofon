<?php

namespace Tests\Browser\Resources;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

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
        $admin = User::factory()->admin()->create();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/resources')
                    ->clickLink('New resource')
                    ->assertPathIs('/resources/create')
                    ->assertSourceHas('form');
        });
    }
}
