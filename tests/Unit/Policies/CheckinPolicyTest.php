<?php

namespace Tests\Unit\Policies;

use App\Booking;
use App\Checkin;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckinPolicyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Only administrators can create checkins.
     *
     * @return void
     */
    public function testOnlyAdminUsersCanCreateCheckins()
    {
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $this->assertTrue($admin->can('create', Checkin::class));
        $this->assertFalse($user->can('create', Checkin::class));
    }

    /**
     * Only administrators can delete a checkin.
     *
     * @return void
     */
    public function testOnlyAdminUsersCanDeleteACheckin()
    {
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $checkin = factory(Booking::class)->create()->checkin()->create();

        $this->assertTrue($admin->can('delete', $checkin));
        $this->assertFalse($user->can('delete', $checkin));
    }
}
