<?php

namespace Tests\Unit\Model;

use Hydrofon\Resource;
use Hydrofon\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Resource can have bookings.
     *
     * @return void
     */
    public function testResourceCanHaveBookings()
    {
        $resource = factory(Resource::class)->create();

        $this->assertInstanceOf(Collection::class, $resource->bookings);
    }

    /**
     * Resource can belong to categories.
     *
     * @return void
     */
    public function testResourceCanBelongToCategories()
    {
        $this->actingAs(factory(User::class)->states('admin')->create());

        $resource = factory(Resource::class)->create();

        $this->assertInstanceOf(Collection::class, $resource->categories);
    }

    /**
     * Resource can belong to a group.
     *
     * @return void
     */
    public function testResourceCanBelongToAGroup()
    {
        $resource = factory(Resource::class)->create();

        $this->assertInstanceOf(Collection::class, $resource->groups);
    }
}
