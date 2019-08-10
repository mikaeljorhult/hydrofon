<?php

namespace Tests\Browser\Buckets;

use Hydrofon\User;
use Hydrofon\Bucket;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EditTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * User can navigate to edit page from buckets index.
     *
     * @return void
     */
    public function testUserCanNavigateToEditPage()
    {
        $admin = factory(User::class)->states('admin')->create();
        $bucket = factory(Bucket::class)->create();

        $this->browse(function (Browser $browser) use ($admin, $bucket) {
            $browser->loginAs($admin)
                    ->visit('/buckets')
                    ->clickLink($bucket->name)
                    ->assertPathIs('/buckets/'.$bucket->id.'/edit')
                    ->assertSourceHas('form');
        });
    }
}
