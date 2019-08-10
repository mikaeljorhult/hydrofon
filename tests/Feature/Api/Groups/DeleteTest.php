<?php

namespace Tests\Feature\Api\Groups;

use Hydrofon\User;
use Hydrofon\Group;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
