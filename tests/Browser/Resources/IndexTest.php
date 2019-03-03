<?php

namespace Tests\Browser\Resources;

use Hydrofon\Resource;
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
        $admin = factory(User::class)->states('admin')->create();

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
        $user = factory(User::class)->create();
        $resource = factory(Resource::class)->create();

        $this->browse(function (Browser $browser) use ($user, $resource) {
            $browser->loginAs($user)
                    ->visit('/resources')
                    ->assertSee($resource->name);
        });
    }
}
