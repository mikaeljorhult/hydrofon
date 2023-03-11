<?php

namespace Tests\Feature\Subscriptions;

use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A user subscription can be shown.
     */
    public function testUserSubscriptionIsShown(): void
    {
        $subscription = Subscription::factory()->user()->create();

        $this->get('feeds/'.$subscription->uuid)
             ->assertStatus(200)
             ->assertHeader('Content-Type', 'text/calendar; charset=UTF-8');
    }
}
