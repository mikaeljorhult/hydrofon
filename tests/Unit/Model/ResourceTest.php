<?php

namespace Tests\Unit\Model;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Resource can have bookings.
     */
    public function testResourceCanHaveBookings(): void
    {
        $resource = Resource::factory()->create();

        $this->assertInstanceOf(Collection::class, $resource->bookings);
    }

    /**
     * Resource can belong to categories.
     */
    public function testResourceCanBelongToCategories(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $resource = Resource::factory()->create();

        $this->assertInstanceOf(Collection::class, $resource->categories);
    }

    /**
     * Resource can belong to a group.
     */
    public function testResourceCanBelongToAGroup(): void
    {
        $resource = Resource::factory()->create();

        $this->assertInstanceOf(Collection::class, $resource->groups);
    }
}
