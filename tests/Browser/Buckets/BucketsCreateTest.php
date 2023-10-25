<?php

namespace Tests\Browser\Buckets;

use App\Models\Bucket;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class BucketsCreateTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testCreateRouteIsReachable(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/buckets')
                ->assertSeeLink('New bucket')
                ->clickLink('New bucket')
                ->assertPathIs('/buckets/create')
                ->assertSee('Create bucket')
                ->logout();
        });
    }

    public function testItemCanBeCreated(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/buckets/create')
                ->assertSee('Create bucket')
                ->type('name', 'New bucket')
                ->clickAndWaitForReload('@submitcreate')
                ->assertPathIs('/buckets')
                ->logout();
        });

        $this->assertDatabaseHas(Bucket::class, [
            'name' => 'New bucket',
        ]);
    }

    public function testCreateCanBeCancelled(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/buckets/create')
                ->click('@submitcancel')
                ->assertPathIs('/buckets')
                ->logout();
        });
    }
}
