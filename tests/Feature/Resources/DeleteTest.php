<?php

namespace Tests\Feature\Resources;

use Hydrofon\User;
use Tests\TestCase;
use Hydrofon\Booking;
use Hydrofon\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Resources can be deleted.
     *
     * @return void
     */
    public function testResourcesCanBeDeleted()
    {
        $admin = factory(User::class)->states('admin')->create();
        $resource = factory(Resource::class)->create();

        $response = $this->actingAs($admin)->delete('resources/'.$resource->id);

        $response->assertRedirect('/resources');
        $this->assertDatabaseMissing('resources', [
            'name' => $resource->name,
        ]);
    }

    /**
     * Bookings of deleted resource is also deleted.
     *
     * @return void
     */
    public function testRelatedBookingsAreDeletedWithResource()
    {
        $admin = factory(User::class)->states('admin')->create();
        $booking = factory(Booking::class)->create();

        $this->actingAs($admin)->delete('resources/'.$booking->resource->id);

        $this->assertDatabaseMissing('resources', [
            'id' => $booking->resource->id,
        ]);
        $this->assertDatabaseMissing('bookings', [
            'id' => $booking->id,
        ]);
    }

    /**
     * Non-admin users can not delete resources.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotDeleteResources()
    {
        $user = factory(User::class)->create();
        $resource = factory(Resource::class)->create();

        $response = $this->actingAs($user)->delete('resources/'.$resource->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('resources', [
            'id'   => $resource->id,
            'name' => $resource->name,
        ]);
    }
}
