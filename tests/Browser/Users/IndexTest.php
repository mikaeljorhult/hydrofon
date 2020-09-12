<?php

namespace Tests\Browser\Users;

use App\Models\User;
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
        $user = User::factory()->admin()->create();

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
        $admin = User::factory()->admin()->create();
        $otherUser = User::factory()->create();

        $this->browse(function (Browser $browser) use ($admin, $otherUser) {
            $browser->loginAs($admin)
                    ->visit('/users')
                    ->assertSee($otherUser->name);
        });
    }
}
