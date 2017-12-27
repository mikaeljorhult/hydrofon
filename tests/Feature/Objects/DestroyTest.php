<?php

namespace Tests\Feature\Objects;

use Hydrofon\Booking;
use Hydrofon\Object;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Objects can be deleted.
     *
     * @return void
     */
    public function testObjectsCanBeDeleted()
    {
        $user   = factory(User::class)->create();
        $object = factory(Object::class)->create();

        $response = $this->actingAs($user)->delete('objects/' . $object->id);

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
        $user   = factory(User::class)->create();
        $booking = factory(Booking::class)->create();

        $this->actingAs($user)->delete('objects/' . $booking->object->id);

        $this->assertDatabaseMissing('objects', [
            'id' => $booking->object->id,
        ]);
        $this->assertDatabaseMissing('bookings', [
            'id' => $booking->id,
        ]);
    }
}
