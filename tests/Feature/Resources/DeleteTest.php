<?php

namespace Tests\Feature\Resources;

use App\Booking;
use App\Resource;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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
        $admin = User::factory()->admin()->create();
        $resource = Resource::factory()->create();

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
        $admin = User::factory()->admin()->create();
        $booking = Booking::factory()->create();

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
        $user = User::factory()->create();
        $resource = Resource::factory()->create();

        $response = $this->actingAs($user)->delete('resources/'.$resource->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('resources', [
            'id'   => $resource->id,
            'name' => $resource->name,
        ]);
    }
}
