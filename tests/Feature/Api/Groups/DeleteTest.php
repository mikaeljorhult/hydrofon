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
        $admin = factory(User::class)->states('admin')->create();
        $group = factory(Group::class)->create();

        $response = $this->actingAs($admin)->delete('api/groups/'.$group->id, ['ACCEPT' => 'application/json']);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('groups', [
            'id' => $group->id,
        ]);
    }
}
