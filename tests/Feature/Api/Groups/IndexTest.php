<?php

namespace Tests\Feature\Api\Groups;

use Hydrofon\Group;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Groups are listed in index.
     *
     * @return void
     */
    public function testGroupsAreListed()
    {
        $group = factory(Group::class)->create();

        $this->actingAs(factory(User::class)->create())
             ->getJson('api/groups')
             ->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                     '*' => [
                         'id',
                         'name',
                     ],
                 ],
             ])
             ->assertJsonFragment([
                 'id'   => $group->id,
                 'name' => $group->name,
             ]);
    }

    /**
     * Groups can be filtered by name.
     *
     * @return void
     */
    public function testGroupsAreFilteredByName()
    {
        $excludedGroup = factory(Group::class)->create(['name' => 'Excluded Group']);
        $includedGroup = factory(Group::class)->create(['name' => 'Included Group']);

        $this->actingAs(factory(User::class)->create())
             ->getJson('api/groups?filter[name]=included')
             ->assertStatus(200)
             ->assertJsonFragment([
                 'id' => $includedGroup->id,
             ])
             ->assertJsonMissing([
                 'id' => $excludedGroup->id,
             ]);
    }
}
