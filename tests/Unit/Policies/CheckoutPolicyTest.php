<?php

namespace Tests\Unit\Policies;

use App\Booking;
use App\Checkout;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutPolicyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Only administrators can create checkouts.
     *
     * @return void
     */
    public function testOnlyAdminUsersCanCreateCheckouts()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->assertTrue($admin->can('create', Checkout::class));
        $this->assertFalse($user->can('create', Checkout::class));
    }

    /**
     * Only administrators can delete a checkin.
     *
     * @return void
     */
    public function testOnlyAdminUsersCanDeleteACheckout()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $checkout = Booking::factory()->create()->checkin()->create();

        $this->assertTrue($admin->can('delete', $checkout));
        $this->assertFalse($user->can('delete', $checkout));
    }
}
