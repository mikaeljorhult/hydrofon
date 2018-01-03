<?php

namespace Tests\Unit\Model;

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
}
