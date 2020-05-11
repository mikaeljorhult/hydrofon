<?php

namespace Tests\Unit\Model;

use App\Group;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Group can have resources.
     *
     * @return void
     */
    public function testGroupCanHaveResources()
    {
        $this->actingAs(factory(User::class)->states('admin')->create());

        $group = factory(Group::class)->create();

        $this->assertInstanceOf(Collection::class, $group->resources);
    }

    /**
     * Group can have users.
     *
     * @return void
     */
    public function testGroupCanHaveUsers()
    {
        $group = factory(Group::class)->create();

        $this->assertInstanceOf(Collection::class, $group->users);
    }
}
