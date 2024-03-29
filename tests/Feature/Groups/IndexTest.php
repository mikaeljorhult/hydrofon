<?php

namespace Tests\Feature\Groups;

use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Groups are listed in index.
     */
    public function testGroupsAreListed(): void
    {
        $group = Group::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->get('groups')
            ->assertSuccessful()
            ->assertSee($group->name);
    }

    /**
     * Groups index can be filtered by name.
     */
    public function testGroupsCanBeFilteredByName(): void
    {
        $visibleGroup = Group::factory()->create();
        $notVisibleGroup = Group::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->get('groups?'.http_build_query([
                'filter[name]' => $visibleGroup->name,
            ]))
            ->assertSuccessful()
            ->assertSee($visibleGroup->name)
            ->assertDontSee($notVisibleGroup->name);
    }
}
