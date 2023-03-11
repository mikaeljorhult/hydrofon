<?php

namespace Tests\Feature\Resources;

use App\Models\Booking;
use App\Models\Resource;
use App\Models\User;
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
    public function testResourcesCanBeDeleted(): void
    {
        $admin = User::factory()->admin()->create();
        $resource = Resource::factory()->create();

        $response = $this->actingAs($admin)->delete('resources/'.$resource->id);

        $response->assertRedirect('/resources');
        $this->assertModelMissing($resource);
    }

    /**
     * Bookings of deleted resource is also deleted.
     *
     * @return void
     */
    public function testRelatedBookingsAreDeletedWithResource(): void
    {
        $admin = User::factory()->admin()->create();
        $booking = Booking::factory()->create();

        $this->actingAs($admin)->delete('resources/'.$booking->resource->id);

        $this->assertModelMissing($booking);
        $this->assertModelMissing($booking->resource);
    }

    /**
     * Non-admin users can not delete resources.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotDeleteResources(): void
    {
        $user = User::factory()->create();
        $resource = Resource::factory()->create();

        $response = $this->actingAs($user)->delete('resources/'.$resource->id);

        $response->assertStatus(403);
        $this->assertModelExists($resource);
    }
}
