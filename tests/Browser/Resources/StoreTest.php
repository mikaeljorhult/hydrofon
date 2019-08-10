<?php

namespace Tests\Browser\Resources;

use Hydrofon\User;
use Hydrofon\Resource;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class StoreTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Resources can be stored through create form.
     *
     * @return void
     */
    public function testResourcesCanBeStored()
    {
        $admin = factory(User::class)->states('admin')->create();
        $resource = factory(Resource::class)->make();

        $this->browse(function (Browser $browser) use ($admin, $resource) {
            $browser->loginAs($admin)
                    ->visit('/resources/create')
                    ->type('name', $resource->name)
                    ->press('Create')
                    ->assertPathIs('/resources')
                    ->assertSee($resource->name);
        });
    }

    /**
     * Requests with incomplete data is redirected back to create form.
     *
     * @return void
     */
    public function testInvalidResourceIsRedirectedBackToCreateForm()
    {
        $admin = factory(User::class)->states('admin')->create();
        $resource = factory(Resource::class)->make();

        $this->browse(function (Browser $browser) use ($admin, $resource) {
            $browser->loginAs($admin)
                    ->visit('/resources/create')
                    ->press('Create')
                    ->assertPathIs('/resources/create');
        });
    }
}
