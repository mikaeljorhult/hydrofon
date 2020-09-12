<?php

namespace Tests\Browser\Buckets;

use App\Models\Bucket;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

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
        $admin = User::factory()->admin()->create();
        $bucket = Bucket::factory()->make();

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
        $admin = User::factory()->admin()->create();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/buckets/create')
                    ->press('Create')
                    ->assertPathIs('/buckets/create');
        });
    }
}
