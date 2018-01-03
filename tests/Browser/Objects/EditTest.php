<?php

namespace Tests\Browser\Objects;

use Hydrofon\Object;
use Hydrofon\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class EditTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * User can navigate to edit page from objects index.
     *
     * @return void
     */
    public function testUserCanNavigateToEditPage()
    {
        $admin  = factory(User::class)->states('admin')->create();
        $object = factory(Object::class)->create();

        $this->browse(function (Browser $browser) use ($admin, $object) {
            $browser->loginAs($admin)
                    ->visit('/objects')
                    ->clickLink($object->name)
                    ->assertPathIs('/objects/' . $object->id . '/edit')
                    ->assertSourceHas('form');
        });
    }
}
