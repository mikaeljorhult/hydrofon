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
        $group = factory(Group::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->putJson('api/groups/'.$group->id, [
                 'name' => 'Updated Name',
             ])
             ->assertStatus(202)
             ->assertJsonStructure([
                 'id',
                 'name',
             ])
             ->assertJsonFragment([
                 'id'   => $group->id,
                 'name' => 'Updated Name',
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
        $group = factory(Group::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->putJson('api/groups/'.$group->id, [
                 'name' => '',
             ])
             ->assertStatus(422)
             ->assertJsonValidationErrors('name');

        $this->assertDatabaseHas('groups', [
            'name' => $group->name,
        ]);
    }
}
