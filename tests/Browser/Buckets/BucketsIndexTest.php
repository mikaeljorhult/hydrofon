<?php

namespace Tests\Browser\Buckets;

use App\Models\Bucket;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class BucketsIndexTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testBucketsIndexIsReachable(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/calendar')
                ->assertSeeLink('Buckets')
                ->clickLink('Buckets')
                ->assertPathIs('/buckets')
                ->assertSee('Buckets')
                ->logout();
        });
    }

    public function testNameIsDisplayedInListing(): void
    {
        $bucket = Bucket::factory()->create();

        $this->browse(function (Browser $browser) use ($bucket) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/buckets')
                ->assertSee($bucket->name)
                ->logout();
        });
    }

    public function testItemCanBeEditedInline(): void
    {
        $bucket = Bucket::factory()->create();
        Resource::factory()->create();

        $this->browse(function (Browser $browser) use ($bucket) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/buckets')
                ->mouseover('@item-'.$bucket->id)
                ->click('@inline-edit')
                ->waitFor('@inline-item-'.$bucket->id)
                ->type('name', 'New name')
                ->pause(500)
                ->press('Save')
                ->waitFor('@item-'.$bucket->id)
                ->assertSee('New name')
                ->logout();
        });

        $this->assertDatabaseHas(Bucket::class, [
            'id' => $bucket->id,
            'name' => 'New name',
        ]);
    }

    public function testInlineEditingCanBeCancelled(): void
    {
        $bucket = Bucket::factory()->create();

        $this->browse(function (Browser $browser) use ($bucket) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/buckets')
                ->mouseover('@item-'.$bucket->id)
                ->click('@inline-edit')
                ->waitFor('@inline-item-'.$bucket->id)
                ->press('Cancel')
                ->waitFor('@item-'.$bucket->id)
                ->assertSee($bucket->name)
                ->logout();
        });
    }

    public function testItemCanBeDeleted(): void
    {
        $bucket = Bucket::factory()->create();

        $this->browse(function (Browser $browser) use ($bucket) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/buckets')
                ->mouseover('@item-'.$bucket->id)
                ->click('@delete')
                ->waitUntilMissing('@item-'.$bucket->id)
                ->assertDontSee($bucket->name)
                ->logout();
        });
    }

    public function testBucketsCreateIsReachable(): void
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

    public function testMultipleItemsCanBeDeleted(): void
    {
        $buckets = Bucket::factory(5)->create();

        $this->browse(function (Browser $browser) use ($buckets) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/buckets')
                ->check('[name="selected[]"][value="'.$buckets->first()->id.'"]')
                ->check('[name="selected[]"][value="'.$buckets->last()->id.'"]')
                ->click('@delete-multiple')
                ->waitUntilMissing('@item-'.$buckets->first()->id)
                ->logout();
        });

        $this->assertDatabaseCount(Bucket::class, 3);
        $this->assertModelMissing($buckets->first());
        $this->assertModelMissing($buckets->last());
    }
}
