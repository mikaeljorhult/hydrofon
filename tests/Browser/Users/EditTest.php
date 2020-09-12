<?php

namespace Tests\Browser\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class EditTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * User can navigate to edit page from users index.
     *
     * @return void
     */
    public function testUserCanNavigateToEditPage()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($admin, $user) {
            $browser->loginAs($admin)
                    ->visit('/users')
                    ->clickLink($user->email)
                    ->assertPathIs('/users/'.$user->id.'/edit')
                    ->assertSourceHas('form');
        });
    }
}
