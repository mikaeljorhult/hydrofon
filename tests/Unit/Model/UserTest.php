<?php

namespace Tests\Unit\Model;

use Hydrofon\Booking;
use Hydrofon\User;
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
        $user    = factory(User::class)->create();
        $booking = factory(Booking::class)->create();

        $this->assertFalse($user->owns($booking));
        $booking->user_id = $user->id;
        $this->assertTrue($user->owns($booking));
    }
}
