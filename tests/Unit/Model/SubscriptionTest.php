<?php

namespace Tests\Unit\Model;

use Hydrofon\Subscription;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A UUID is automatically created when storing subscription.
     *
     * @return void
     */
    public function testUuidIsCreated()
    {
        $subscription = factory(Subscription::class)->create();

        $this->assertNotNull($subscription->uuid);
    }
}
