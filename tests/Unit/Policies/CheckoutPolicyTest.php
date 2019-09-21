<?php

namespace Tests\Unit\Policies;

use Hydrofon\Booking;
use Hydrofon\Checkout;
use Hydrofon\User;
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
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

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
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $checkout = factory(Booking::class)->create()->checkin()->create();

        $this->assertTrue($admin->can('delete', $checkout));
        $this->assertFalse($user->can('delete', $checkout));
    }
}
