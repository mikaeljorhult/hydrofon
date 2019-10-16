<?php

namespace Tests\Feature\Api\Groups;

use Hydrofon\Group;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Groups can be deleted.
     *
     * @return void
     */
    public function testGroupsCanBeDeleted()
    {
        $group = factory(Group::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->deleteJson('api/groups/'.$group->id)
             ->assertStatus(204);

        $this->assertDatabaseMissing('groups', [
            'id' => $group->id,
        ]);
    }
}
