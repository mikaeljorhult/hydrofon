<?php

namespace Tests\Browser\Resources;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UpdateTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Resources can be updated through create form.
     *
     * @return void
     */
    public function testResourcesCanBeUpdated()
    {
        $admin = User::factory()->admin()->create();
        $resource = Resource::factory()->create();

        $this->browse(function (Browser $browser) use ($admin, $resource) {
            $browser->loginAs($admin)
                    ->visit('/resources/'.$resource->id.'/edit')
                    ->type('name', 'New resource Name')
                    ->press('Update')
                    ->assertPathIs('/resources')
                    ->assertSee('New resource Name');
        });
    }

    /**
     * Resources must have a name.
     *
     * @return void
     */
    public function testResourcesMustHaveAName()
    {
        $admin = User::factory()->admin()->create();
        $resource = Resource::factory()->create();

        $this->browse(function (Browser $browser) use ($admin, $resource) {
            $browser->loginAs($admin)
                    ->visit('/resources/'.$resource->id.'/edit')
                    ->type('name', '')
                    ->press('Update')
                    ->assertPathIs('/resources/'.$resource->id.'/edit');
        });
    }
}
