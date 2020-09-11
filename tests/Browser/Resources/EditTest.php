<?php

namespace Tests\Browser\Resources;

use App\Resource;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

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
        $admin = User::factory()->admin()->create();
        $resource = Resource::factory()->create();

        $this->browse(function (Browser $browser) use ($admin, $resource) {
            $browser->loginAs($admin)
                    ->visit('/resources')
                    ->clickLink($resource->name)
                    ->assertPathIs('/resources/'.$resource->id.'/edit')
                    ->assertSourceHas('form');
        });
    }
}
