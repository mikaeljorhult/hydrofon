<?php

namespace Tests\Feature\Subscriptions;

use App\Subscription;
use App\User;
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
        $user = User::factory()->create();
        $subscription = Subscription::factory()->create([
            'subscribable_type' => \App\User::class,
            'subscribable_id'   => $user->id,
        ]);

        $this->get('feeds/'.$subscription->uuid)
             ->assertStatus(200)
             ->assertHeader('Content-Type', 'text/calendar; charset=UTF-8');
    }
}
