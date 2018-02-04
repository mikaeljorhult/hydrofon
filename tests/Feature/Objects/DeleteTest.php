<?php

namespace Tests\Feature\Objects;

use Hydrofon\Booking;
use Hydrofon\Object;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Objects can be deleted.
     *
     * @return void
     */
    public function testObjectsCanBeDeleted()
    {
        $admin = factory(User::class)->states('admin')->create();
        $object = factory(Object::class)->create();

        $response = $this->actingAs($admin)->delete('objects/'.$object->id);

        $response->assertRedirect('/objects');
        $this->assertDatabaseMissing('objects', [
            'name' => $object->name,
        ]);
    }

    /**
     * Bookings of deleted object is also deleted.
     *
     * @return void
     */
    public function testRelatedBookingsAreDeletedWithObject()
    {
        $admin = factory(User::class)->states('admin')->create();
        $booking = factory(Booking::class)->create();

        $this->actingAs($admin)->delete('objects/'.$booking->object->id);

        $this->assertDatabaseMissing('objects', [
            'id' => $booking->object->id,
        ]);
        $this->assertDatabaseMissing('bookings', [
            'id' => $booking->id,
        ]);
    }

    /**
     * Non-admin users can not delete objects.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotDeleteObjects()
    {
        $user = factory(User::class)->create();
        $object = factory(Object::class)->create();

        $response = $this->actingAs($user)->delete('objects/'.$object->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('objects', [
            'id'   => $object->id,
            'name' => $object->name,
        ]);
    }
}
