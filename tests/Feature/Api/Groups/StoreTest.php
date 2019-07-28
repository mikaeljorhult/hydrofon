<?php

namespace Tests\Feature\Api\Groups;

use Hydrofon\Group;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Groups can be created and stored.
     *
     * @return void
     */
    public function testGroupsCanBeStored()
    {
        $group = factory(Group::class)->make();

        $response = $this->actingAs(factory(User::class)->states('admin')->create())
                         ->post('api/groups', $group->toArray(), ['ACCEPT' => 'application/json']);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'id',
                     'name',
                 ])
                 ->assertJsonFragment([
                     'name'  => $group->name,
                 ]);

        $this->assertDatabaseHas('groups', [
            'id'    => 1,
            'name'  => $group->name,
        ]);
    }

    /**
     * Groups must have a name.
     *
     * @return void
     */
    public function testGroupsMustHaveAName()
    {
        $group = factory(Group::class)->make(['name' => null]);

        $response = $this->actingAs(factory(User::class)->states('admin')->create())
                         ->post('api/groups', $group->toArray(), ['ACCEPT' => 'application/json']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('name');

        $this->assertEquals(0, \Hydrofon\Group::count());
    }
}
