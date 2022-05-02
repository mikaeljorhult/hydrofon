<?php

namespace Tests\Feature\Subscriptions;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Posts request to delete a subscription.
     *
     * @param  \App\Models\Subscription  $subscription
     * @param  \App\Models\User|null  $user
     * @return \Illuminate\Testing\TestResponse
     */
    public function deleteSubscription($subscription, $user = null)
    {
        return $this->actingAs($user ?: User::factory()->admin()->create())
                    ->delete('subscriptions/'.$subscription->id);
    }

    /**
     * A user can delete subscription of their own bookings.
     *
     * @return void
     */
    public function testUserSubscriptionsCanBeDeleted()
    {
        $subscription = Subscription::factory()->user()->create();

        $this->from(route('users.show', [$subscription->subscribable->id]))
             ->deleteSubscription($subscription, $subscription->subscribable)
             ->assertRedirect(route('users.show', [$subscription->subscribable->id]));

        $this->assertModelMissing($subscription);
    }

    /**
     * An administrator can delete subscription of another users bookings.
     *
     * @return void
     */
    public function testAdministratorCanDeleteAnyUserSubscription()
    {
        $subscription = Subscription::factory()->user()->create();

        $this->from(route('users.show', [$subscription->subscribable->id]))
             ->deleteSubscription($subscription)
             ->assertRedirect(route('users.show', [$subscription->subscribable->id]));

        $this->assertModelMissing($subscription);
    }

    /**
     * An administrator can delete subscription of resource bookings.
     *
     * @return void
     */
    public function testAdministratorCanDeleteAResourceSubscription()
    {
        $subscription = Subscription::factory()->resource()->create();

        $this->from(route('resources.show', [$subscription->subscribable->id]))
             ->deleteSubscription($subscription)
             ->assertRedirect(route('resources.show', [$subscription->subscribable->id]));

        $this->assertModelMissing($subscription);
    }

    /**
     * A regular user can not delete subscription of resource bookings.
     *
     * @return void
     */
    public function testUserCanNotDeleteAResourceSubscription()
    {
        $subscription = Subscription::factory()->resource()->create();

        $this->from(route('resources.show', [$subscription->subscribable->id]))
             ->deleteSubscription($subscription, User::factory()->create())
             ->assertStatus(403);

        $this->assertModelExists($subscription);
    }
}
