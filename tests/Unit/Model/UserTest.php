<?php

namespace Tests\Unit\Model;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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
        $user = User::factory()->create();

        $this->assertInstanceOf(Collection::class, $user->bookings);
    }

    /**
     * User can belong to a group.
     *
     * @return void
     */
    public function testUserCanBelongToAGroup()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(Collection::class, $user->groups);
    }

    /**
     * User is recognized as administrator if is_admin attribute is true.
     *
     * @return void
     */
    public function testUserCanBeAdmin()
    {
        $user = User::factory()->create(['is_admin' => false]);

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
        $user = User::factory()->create();
        $booking = Booking::factory()->create();

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
        $user = User::factory()->create();

        $rendered = $user->exportToJson();

        $this->assertJson($rendered);
        $this->assertStringContainsString($user->name, $rendered);
    }

    /**
     * Bookings are included in export.
     *
     * @return void
     */
    public function testBookingsAreIncludedInExport()
    {
        $booking = Booking::factory()->create();

        $rendered = $booking->user->exportToJson();

        $this->assertJson($rendered);
        $this->assertStringContainsString($booking->resource->name, $rendered);
    }

    /**
     * Identifiers are included in export.
     *
     * @return void
     */
    public function testIdentifiersAreIncludedInExport()
    {
        $user = User::factory()->create();
        $user->identifiers()->create($identifier = [
            'identifiable_type' => User::class,
            'identifiable_id'   => $user->id,
            'value'             => 'new-identifier',
        ]);

        $rendered = $user->exportToJson();

        $this->assertJson($rendered);
        $this->assertStringContainsString('new-identifier', $rendered);
    }
}
