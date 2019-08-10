<?php

namespace Tests\Browser\Buckets;

use Hydrofon\User;
use Hydrofon\Bucket;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class StoreTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Buckets can be stored through create form.
     *
     * @return void
     */
    public function testBucketsCanBeStored()
    {
        $admin = factory(User::class)->states('admin')->create();
        $bucket = factory(Bucket::class)->make();

        $this->browse(function (Browser $browser) use ($admin, $bucket) {
            $browser->loginAs($admin)
                    ->visit('/buckets/create')
                    ->type('name', $bucket->name)
                    ->press('Create')
                    ->assertPathIs('/buckets')
                    ->assertSee($bucket->name);
        });
    }

    /**
     * Requests with incomplete data is redirected back to create form.
     *
     * @return void
     */
    public function testInvalidBucketIsRedirectedBackToCreateForm()
    {
        $admin = factory(User::class)->states('admin')->create();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/buckets/create')
                    ->press('Create')
                    ->assertPathIs('/buckets/create');
        });
    }
}
