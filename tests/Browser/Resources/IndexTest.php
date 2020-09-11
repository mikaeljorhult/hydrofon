<?php

namespace Tests\Browser\Resources;

use App\Resource;
use App\User;
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
        $admin = User::factory()->admin()->create();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/calendar')
                    ->clickLink('Resources')
                    ->assertPathIs('/resources');
        });
    }

    /**
     * Available resources are displayed on index page.
     *
     * @return void
     */
    public function testResourceIsListedOnIndexPage()
    {
        $user = User::factory()->create();
        $resource = Resource::factory()->create();

        $this->browse(function (Browser $browser) use ($user, $resource) {
            $browser->loginAs($user)
                    ->visit('/resources')
                    ->assertSee($resource->name);
        });
    }
}
