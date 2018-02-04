<?php

namespace Tests\Feature\Objects;

use Hydrofon\Object;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Objects can be updated.
     *
     * @return void
     */
    public function testObjectsCanBeUpdated()
    {
        $admin = factory(User::class)->states('admin')->create();
        $object = factory(Object::class)->create();

        $response = $this->actingAs($admin)->put('objects/'.$object->id, [
            'name' => 'New Object Name',
        ]);

        $response->assertRedirect('/objects');
        $this->assertDatabaseHas('objects', [
            'name' => 'New Object Name',
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
        $object = factory(Object::class)->create();

        $response = $this->actingAs($admin)->put('objects/'.$object->id, [
            'name' => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('name');
        $this->assertDatabaseHas('objects', [
            'name' => $object->name,
        ]);
    }

    /**
     * Non-admin users can not update objects.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotUpdateObjects()
    {
        $user = factory(User::class)->create();
        $object = factory(Object::class)->create();

        $response = $this->actingAs($user)->put('objects/'.$object->id, [
            'name' => 'New Object Name',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('objects', [
            'id'   => $object->id,
            'name' => $object->name,
        ]);
    }
}
