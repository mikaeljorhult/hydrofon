<?php

namespace Tests\Browser\Resources;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ResourcesCreateTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testCreateRouteIsReachable(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/resources')
                ->assertSeeLink('New resource')
                ->clickLink('New resource')
                ->assertPathIs('/resources/create')
                ->assertSee('Create resource')
                ->logout();
        });
    }

    public function testItemCanBeCreated(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/resources/create')
                ->assertSee('Create resource')
                ->type('name', 'New resource')
                ->clickAndWaitForReload('@submitcreate')
                ->assertPathIs('/resources')
                ->logout();
        });

        $this->assertDatabaseHas(Resource::class, [
            'name' => 'New resource',
        ]);
    }

    public function testCreateCanBeCancelled(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/resources/create')
                ->click('@submitcancel')
                ->assertPathIs('/resources')
                ->logout();
        });
    }
}
