<?php

namespace Tests\Feature\Users;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Users can be deleted.
     *
     * @return void
     */
    public function testUsersCanBeDeleted(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->delete('users/'.$user->id);

        $response->assertRedirect('/users');
        $this->assertModelMissing($user);
    }

    /**
     * Bookings of deleted user are also deleted.
     *
     * @return void
     */
    public function testRelatedBookingsAreDeletedWithUser(): void
    {
        $admin = User::factory()->admin()->create();
        $booking = Booking::factory()->create();

        $this->actingAs($admin)->delete('users/'.$booking->user->id);

        $this->assertModelMissing($booking->user);
        $this->assertModelMissing($booking);
    }

    /**
     * Non-admin users can not delete other users.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotDeleteUsers(): void
    {
        $notAdmin = User::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($notAdmin)->delete('users/'.$user->id);

        $response->assertStatus(403);
        $this->assertModelExists($user);
    }

    /**
     * Users can not delete themselves.
     *
     * @return void
     */
    public function testUsersCanNotDeleteThemselves(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->delete('users/'.$admin->id);

        $response->assertStatus(403);
        $this->assertModelExists($admin);
    }
}
