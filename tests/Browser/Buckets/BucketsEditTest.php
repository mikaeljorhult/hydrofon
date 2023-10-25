<?php

namespace Tests\Browser\Buckets;

use App\Models\Bucket;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class BucketsEditTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testEditRouteIsReachable(): void
    {
        $bucket = Bucket::factory()->create();

        $this->browse(function (Browser $browser) use ($bucket) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/buckets')
                ->assertSeeLink($bucket->name)
                ->clickLink($bucket->name)
                ->assertPathIs('/buckets/'.$bucket->id.'/edit')
                ->assertSee('Edit bucket')
                ->logout();
        });
    }

    public function testItemCanBeEdited(): void
    {
        $bucket = Bucket::factory()->create();

        $this->browse(function (Browser $browser) use ($bucket) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/buckets/'.$bucket->id.'/edit')
                ->assertSee('Edit bucket')
                ->type('name', 'New name')
                ->clickAndWaitForReload('@submitupdate')
                ->assertPathIs('/buckets')
                ->assertSee('New name')
                ->logout();
        });

        $this->assertDatabaseHas(Bucket::class, [
            'id' => $bucket->id,
            'name' => 'New name',
        ]);
    }

    public function testEditCanBeCancelled(): void
    {
        $bucket = Bucket::factory()->create();

        $this->browse(function (Browser $browser) use ($bucket) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/buckets/'.$bucket->id.'/edit')
                ->click('@submitcancel')
                ->assertPathIs('/buckets')
                ->logout();
        });
    }
}
