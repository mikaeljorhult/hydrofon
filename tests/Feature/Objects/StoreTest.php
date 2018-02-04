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
        $admin = factory(User::class)->states('admin')->create();
        $object = factory(Object::class)->make();

        $response = $this->actingAs($admin)->post('objects', [
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
        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)->post('objects', [
            'name' => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('name');
        $this->assertCount(0, Object::all());
    }

    /**
     * Non-admin users can not store objects.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotStoreObjects()
    {
        $user = factory(User::class)->create();
        $object = factory(Object::class)->make();

        $response = $this->actingAs($user)->post('objects', [
            'name' => $object->name,
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('objects', [
            'name' => $object->name,
        ]);
    }
}
