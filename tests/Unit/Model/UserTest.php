<?php

namespace Tests\Unit\Model;

use Hydrofon\User;
use Tests\TestCase;
use Hydrofon\Booking;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * User can have bookings.
     *
     * @return void
     */
    public function testUserCanHaveBookings()
    {
        $user = factory(User::class)->create();

        $this->assertInstanceOf(Collection::class, $user->bookings);
    }

    /**
     * User can belong to a group.
     *
     * @return void
     */
    public function testUserCanBelongToAGroup()
    {
        $user = factory(User::class)->create();

        $this->assertInstanceOf(Collection::class, $user->groups);
    }

    /**
     * User is recognized as administrator if is_admin attribute is true.
     *
     * @return void
     */
    public function testUserCanBeAdmin()
    {
        $user = factory(User::class)->create(['is_admin' => false]);

        $this->assertFalse($user->isAdmin());
        $user->is_admin = true;
        $this->assertTrue($user->isAdmin());
    }

    /**
     * The owns method checks if related model belongs to user.
     *
     * @return void
     */
    public function testOwnMethodChecksIfModelBelongsToUser()
    {
        $user = factory(User::class)->create();
        $booking = factory(Booking::class)->create();

        $this->assertFalse($user->owns($booking));
        $booking->user_id = $user->id;
        $this->assertTrue($user->owns($booking));
    }

    /**
     * A user data request export can be rendered.
     *
     * @return void
     */
    public function testDataRequestExportIsRendered()
    {
        $user = factory(User::class)->create();

        $rendered = $user->exportToJson();

        $this->assertJson($rendered);
        $this->assertContains($user->name, $rendered);
    }

    /**
     * Bookings are included in export.
     *
     * @return void
     */
    public function testBookingsAreIncludedInExport()
    {
        $user = factory(User::class)->create();
        $user->bookings()->save($booking = factory(Booking::class)->create());

        $rendered = $user->exportToJson();

        $this->assertJson($rendered);
        $this->assertContains($booking->resource->name, $rendered);
    }

    /**
     * Identifiers are included in export.
     *
     * @return void
     */
    public function testIdentifiersAreIncludedInExport()
    {
        $user = factory(User::class)->create();
        $user->identifiers()->create($identifier = [
            'identifiable_type' => User::class,
            'identifiable_id'   => $user->id,
            'value'             => 'new-identifier',
        ]);

        $rendered = $user->exportToJson();

        $this->assertJson($rendered);
        $this->assertContains('new-identifier', $rendered);
    }
}
