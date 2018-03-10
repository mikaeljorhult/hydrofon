<?php

namespace Tests\Browser\Buckets;

use Hydrofon\Bucket;
use Hydrofon\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

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
        $admin  = factory(User::class)->states('admin')->create();
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
