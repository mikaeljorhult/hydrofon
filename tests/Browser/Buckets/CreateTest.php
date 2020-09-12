<?php

namespace Tests\Browser\Buckets;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CreateTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * User can navigate to create page from buckets index.
     *
     * @return void
     */
    public function testUserCanNavigateToCreatePage()
    {
        $admin = User::factory()->admin()->create();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/buckets')
                    ->clickLink('New bucket')
                    ->assertPathIs('/buckets/create')
                    ->assertSourceHas('form');
        });
    }
}
