<?php

namespace Tests\Feature\Objects;

use Hydrofon\Object;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Objects can be created and stored.
     *
     * @return void
     */
    public function testObjectsCanBeStored()
    {
        $user   = factory(User::class)->create();
        $object = factory(Object::class)->make();

        $response = $this->actingAs($user)->post('objects', [
            'name' => $object->name,
        ]);

        $response->assertRedirect('/objects');
        $this->assertDatabaseHas('objects', [
            'name' => $object->name,
        ]);
    }

    /**
     * Objects must have a name.
     *
     * @return void
     */
    public function testObjectsMustHaveAName()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post('objects', [
            'name' => '',
        ]);

        $response->assertRedirect();
        $this->assertCount(0, Object::all());
    }
}
