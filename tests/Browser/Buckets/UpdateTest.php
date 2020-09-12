<?php

namespace Tests\Browser\Buckets;

use App\Models\Bucket;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UpdateTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Buckets can be updated through create form.
     *
     * @return void
     */
    public function testBucketsCanBeUpdated()
    {
        $admin = User::factory()->admin()->create();
        $bucket = Bucket::factory()->create();

        $this->browse(function (Browser $browser) use ($admin, $bucket) {
            $browser->loginAs($admin)
                    ->visit('/buckets/'.$bucket->id.'/edit')
                    ->type('name', 'New Bucket Name')
                    ->press('Update')
                    ->assertPathIs('/buckets')
                    ->assertSee('New Bucket Name');
        });
    }

    /**
     * Buckets must have a name.
     *
     * @return void
     */
    public function testBucketsMustHaveAName()
    {
        $admin = User::factory()->admin()->create();
        $bucket = Bucket::factory()->create();

        $this->browse(function (Browser $browser) use ($admin, $bucket) {
            $browser->loginAs($admin)
                    ->visit('/buckets/'.$bucket->id.'/edit')
                    ->type('name', '')
                    ->press('Update')
                    ->assertPathIs('/buckets/'.$bucket->id.'/edit');
        });
    }
}
