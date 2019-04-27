<?php

namespace Tests\Feature\Subscriptions;

use Hydrofon\Subscription;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A user subscription can be shown.
     *
     * @return void
     */
    public function testUserSubscriptionIsShown()
    {
        $user = factory(User::class)->create();
        $subscription = factory(Subscription::class)->create([
            'subscribable_type' => '\Hydrofon\User',
            'subscribable_id'   => $user->id,
        ]);

        $this->get('feeds/'.$subscription->uuid)
             ->assertStatus(200)
             ->assertHeader('Content-Type', 'text/calendar; charset=UTF-8');
    }
}
