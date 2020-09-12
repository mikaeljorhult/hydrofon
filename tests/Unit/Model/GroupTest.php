<?php

namespace Tests\Unit\Model;

use App\Models\Group;
use App\Models\User;
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
        $this->actingAs(User::factory()->admin()->create());

        $group = Group::factory()->create();

        $this->assertInstanceOf(Collection::class, $group->resources);
    }

    /**
     * Group can have users.
     *
     * @return void
     */
    public function testGroupCanHaveUsers()
    {
        $group = Group::factory()->create();

        $this->assertInstanceOf(Collection::class, $group->users);
    }
}
