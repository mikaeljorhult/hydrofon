<?php

namespace Tests\Unit\Policies;

use App\Models\Booking;
use App\Models\Checkin;
use App\Models\User;
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
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

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
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $checkin = Booking::factory()->create()->checkin()->create();

        $this->assertTrue($admin->can('delete', $checkin));
        $this->assertFalse($user->can('delete', $checkin));
    }
}
