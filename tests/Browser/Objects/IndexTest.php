<?php

namespace Tests\Browser\Objects;

use Hydrofon\Object;
use Hydrofon\User;
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
                    ->visit('/home')
                    ->clickLink('Objects')
                    ->assertPathIs('/objects');
        });
    }

    /**
     * Available objects are displayed on index page.
     *
     * @return void
     */
    public function testObjectIsListedOnIndexPage()
    {
        $user = factory(User::class)->create();
        $object = factory(Object::class)->create();

        $this->browse(function (Browser $browser) use ($user, $object) {
            $browser->loginAs($user)
                    ->visit('/objects')
                    ->assertSee($object->name);
        });
    }
}
