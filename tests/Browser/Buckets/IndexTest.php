<?php

namespace Tests\Browser\Buckets;

use Hydrofon\User;
use Hydrofon\Bucket;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class IndexTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * User can navigate to index page from home.
     *
     * @return void
     */
    public function testUserCanNavigateToIndexPage()
    {
        $admin = factory(User::class)->states('admin')->create();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/calendar')
                    ->clickLink('Buckets')
                    ->assertPathIs('/buckets');
        });
    }

    /**
     * Available buckets are displayed on index page.
     *
     * @return void
     */
    public function testBucketIsListedOnIndexPage()
    {
        $user = factory(User::class)->create();
        $bucket = factory(Bucket::class)->create();

        $this->browse(function (Browser $browser) use ($user, $bucket) {
            $browser->loginAs($user)
                    ->visit('/buckets')
                    ->assertSee($bucket->name);
        });
    }
}
