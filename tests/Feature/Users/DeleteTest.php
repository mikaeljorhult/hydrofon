<?php

namespace Tests\Feature\Users;

use App\Booking;
use App\User;
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
    public function testUsersCanBeDeleted()
    {
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($admin)->delete('users/'.$user->id);

        $response->assertRedirect('/users');
        $this->assertDatabaseMissing('users', [
            'email' => $user->email,
        ]);
    }

    /**
     * Bookings of deleted user are also deleted.
     *
     * @return void
     */
    public function testRelatedBookingsAreDeletedWithUser()
    {
        $admin = factory(User::class)->states('admin')->create();
        $booking = factory(Booking::class)->create();

        $this->actingAs($admin)->delete('users/'.$booking->user->id);

        $this->assertDatabaseMissing('users', [
            'id' => $booking->user->id,
        ]);
        $this->assertDatabaseMissing('bookings', [
            'id' => $booking->id,
        ]);
    }

    /**
     * Non-admin users can not delete other users.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotDeleteUsers()
    {
        $notAdmin = factory(User::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($notAdmin)->delete('users/'.$user->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('users', [
            'email' => $user->email,
        ]);
    }

    /**
     * Users can not delete themselves.
     *
     * @return void
     */
    public function testUsersCanNotDeleteThemselves()
    {
        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)->delete('users/'.$admin->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('users', [
            'email' => $admin->email,
        ]);
    }
}
