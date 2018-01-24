<?php

namespace Tests\Unit\Model;

use Hydrofon\Group;
use Hydrofon\Object;
use Hydrofon\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Group can have objects.
     *
     * @return void
     */
    public function testGroupCanHaveObjects()
    {
        $group = factory(Group::class)->create();

        $this->assertInstanceOf(Collection::class, $group->objects);
    }

    /**
     * Group can have objects.
     *
     * @return void
     */
    public function testGroupCanHaveUsers()
    {
        $group = factory(Group::class)->create();

        $this->assertInstanceOf(Collection::class, $group->users);
    }
}
