<?php

namespace Tests\Feature\Users;

use Hydrofon\Booking;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Users can be deleted.
     *
     * @return void
     */
    public function testUsersCanBeDeleted()
    {
        $admin = factory(User::class)->create();
        $user  = factory(User::class)->create();

        $response = $this->actingAs($admin)->delete('users/' . $user->id);

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
        $admin   = factory(User::class)->create();
        $booking = factory(Booking::class)->create();

        $this->actingAs($admin)->delete('users/' . $booking->user->id);

        $this->assertDatabaseMissing('users', [
            'id' => $booking->user->id,
        ]);
        $this->assertDatabaseMissing('bookings', [
            'id' => $booking->id,
        ]);
    }

    /**
     * Users can not delete themselves.
     *
     * @return void
     */
    public function testUsersCanNotDeleteThemselves()
    {
        $admin = factory(User::class)->create();

        $response = $this->actingAs($admin)->delete('users/' . $admin->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('users', [
            'email' => $admin->email,
        ]);
    }
}
