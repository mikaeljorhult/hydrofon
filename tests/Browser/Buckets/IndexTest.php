<?php

namespace Tests\Browser\Buckets;

use App\Bucket;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

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
