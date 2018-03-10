<?php

namespace Tests\Browser\Buckets;

use Hydrofon\Bucket;
use Hydrofon\User;
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
        $admin  = factory(User::class)->states('admin')->create();
        $bucket = factory(Bucket::class)->create();

        $this->browse(function (Browser $browser) use ($admin, $bucket) {
            $browser->loginAs($admin)
                    ->visit('/buckets/'.$bucket->id.'/edit')
                    ->type('name', 'New Bucket Name')
                    ->press('Update')
                    ->assertPathIs('/groups')
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
        $admin  = factory(User::class)->states('admin')->create();
        $bucket = factory(Bucket::class)->create();

        $this->browse(function (Browser $browser) use ($admin, $bucket) {
            $browser->loginAs($admin)
                    ->visit('/buckets/'.$bucket->id.'/edit')
                    ->type('name', '')
                    ->press('Update')
                    ->assertPathIs('/buckets/'.$bucket->id.'/edit');
        });
    }
}
