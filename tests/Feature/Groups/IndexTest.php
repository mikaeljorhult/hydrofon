<?php

namespace Tests\Feature\Groups;

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

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->get('groups')
             ->assertSuccessful()
             ->assertSee($group->name);
    }

    /**
     * Groups index can be filtered by name.
     *
     * @return void
     */
    public function testGroupsCanBeFilteredByName()
    {
        $visibleGroup    = factory(Group::class)->create();
        $notVisibleGroup = factory(Group::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->get('groups?'.http_build_query([
                     'filter' => $visibleGroup->name,
                 ]))
             ->assertSuccessful()
             ->assertSee($visibleGroup->name)
             ->assertDontSee($notVisibleGroup->name);
    }
}
