<?php

namespace Tests\Browser\Resources;

use App\Resource;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

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
        $admin = User::factory()->admin()->create();
        $resource = Resource::factory()->make();

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
        $admin = User::factory()->admin()->create();
        $resource = Resource::factory()->make();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/resources/create')
                    ->press('Create')
                    ->assertPathIs('/resources/create');
        });
    }
}
