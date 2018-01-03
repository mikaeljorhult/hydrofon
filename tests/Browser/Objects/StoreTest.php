<?php

namespace Tests\Browser\Objects;

use Hydrofon\Object;
use Hydrofon\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class StoreTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Objects can be stored through create form.
     *
     * @return void
     */
    public function testObjectsCanBeStored()
    {
        $admin  = factory(User::class)->states('admin')->create();
        $object = factory(Object::class)->make();

        $this->browse(function (Browser $browser) use ($admin, $object) {
            $browser->loginAs($admin)
                    ->visit('/objects/create')
                    ->type('name', $object->name)
                    ->press('Create')
                    ->assertPathIs('/objects')
                    ->assertSee($object->name);
        });
    }

    /**
     * Requests with incomplete data is redirected back to create form.
     *
     * @return void
     */
    public function testInvalidObjectIsRedirectedBackToCreateForm()
    {
        $admin  = factory(User::class)->states('admin')->create();
        $object = factory(Object::class)->make();

        $this->browse(function (Browser $browser) use ($admin, $object) {
            $browser->loginAs($admin)
                    ->visit('/objects/create')
                    ->press('Create')
                    ->assertPathIs('/objects/create');
        });
    }
}
