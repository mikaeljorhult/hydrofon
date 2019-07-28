<?php

namespace Tests\Feature\Api\Groups;

use Hydrofon\Group;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Groups can be updated.
     *
     * @return void
     */
    public function testGroupsCanBeUpdated()
    {
        $admin = factory(User::class)->states('admin')->create();
        $group = factory(Group::class)->create();

        $response = $this->actingAs($admin)->put('api/groups/'.$group->id, [
            'name'  => 'Updated Name',
        ], ['ACCEPT' => 'application/json']);

        $response->assertStatus(202)
                 ->assertJsonStructure([
                     'id',
                     'name',
                 ])
                 ->assertJsonFragment([
                     'id'    => $group->id,
                     'name'  => 'Updated Name',
                 ]);

        $this->assertDatabaseHas('groups', [
            'name' => 'Updated Name',
        ]);
    }

    /**
     * Groups must have a name.
     *
     * @return void
     */
    public function testGroupsMustHaveName()
    {
        $admin = factory(User::class)->states('admin')->create();
        $group = factory(Group::class)->create();

        $response = $this->actingAs($admin)->put('api/groups/'.$group->id, [
            'name'  => '',
        ], ['ACCEPT' => 'application/json']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('name');

        $this->assertDatabaseHas('groups', [
            'name' => $group->name,
        ]);
    }
}
