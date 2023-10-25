<?php

namespace Tests\Browser\Resources;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ResourcesEditTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testEditRouteIsReachable(): void
    {
        $resource = Resource::factory()->create();

        $this->browse(function (Browser $browser) use ($resource) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/resources')
                ->assertSeeLink($resource->name)
                ->clickLink($resource->name)
                ->assertPathIs('/resources/'.$resource->id.'/edit')
                ->assertSee('Edit resource')
                ->logout();
        });
    }

    public function testItemCanBeEdited(): void
    {
        $resource = Resource::factory()->create();

        $this->browse(function (Browser $browser) use ($resource) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/resources/'.$resource->id.'/edit')
                ->assertSee('Edit resource')
                ->type('name', 'New name')
                ->clickAndWaitForReload('@submitupdate')
                ->assertPathIs('/resources')
                ->assertSee('New name')
                ->logout();
        });

        $this->assertDatabaseHas(Resource::class, [
            'id' => $resource->id,
            'name' => 'New name',
        ]);
    }

    public function testEditCanBeCancelled(): void
    {
        $resource = Resource::factory()->create();

        $this->browse(function (Browser $browser) use ($resource) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/resources/'.$resource->id.'/edit')
                ->click('@submitcancel')
                ->assertPathIs('/resources')
                ->logout();
        });
    }
}
