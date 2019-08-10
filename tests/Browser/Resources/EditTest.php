<?php

namespace Tests\Browser\Resources;

use Hydrofon\User;
use Hydrofon\Resource;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EditTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * User can navigate to edit page from resources index.
     *
     * @return void
     */
    public function testUserCanNavigateToEditPage()
    {
        $admin = factory(User::class)->states('admin')->create();
        $resource = factory(Resource::class)->create();

        $this->browse(function (Browser $browser) use ($admin, $resource) {
            $browser->loginAs($admin)
                    ->visit('/resources')
                    ->clickLink($resource->name)
                    ->assertPathIs('/resources/'.$resource->id.'/edit')
                    ->assertSourceHas('form');
        });
    }
}
