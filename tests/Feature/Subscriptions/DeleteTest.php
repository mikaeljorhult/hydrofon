<?php

namespace Tests\Feature\Subscriptions;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Posts request to delete a subscription.
     */
    public function deleteSubscription(Subscription $subscription, User $user = null): TestResponse
    {
        return $this->actingAs($user ?: User::factory()->admin()->create())
            ->delete('subscriptions/'.$subscription->id);
    }

    /**
     * A user can delete subscription of their own bookings.
     */
    public function testUserSubscriptionsCanBeDeleted(): void
    {
        $subscription = Subscription::factory()->user()->create();

        $this->from(route('users.show', [$subscription->subscribable->id]))
            ->deleteSubscription($subscription, $subscription->subscribable)
            ->assertRedirect(route('users.show', [$subscription->subscribable->id]));

        $this->assertModelMissing($subscription);
    }

    /**
     * An administrator can delete subscription of another users bookings.
     */
    public function testAdministratorCanDeleteAnyUserSubscription(): void
    {
        $subscription = Subscription::factory()->user()->create();

        $this->from(route('users.show', [$subscription->subscribable->id]))
            ->deleteSubscription($subscription)
            ->assertRedirect(route('users.show', [$subscription->subscribable->id]));

        $this->assertModelMissing($subscription);
    }

    /**
     * An administrator can delete subscription of resource bookings.
     */
    public function testAdministratorCanDeleteAResourceSubscription(): void
    {
        $subscription = Subscription::factory()->resource()->create();

        $this->from(route('resources.show', [$subscription->subscribable->id]))
            ->deleteSubscription($subscription)
            ->assertRedirect(route('resources.show', [$subscription->subscribable->id]));

        $this->assertModelMissing($subscription);
    }

    /**
     * A regular user can not delete subscription of resource bookings.
     */
    public function testUserCanNotDeleteAResourceSubscription(): void
    {
        $subscription = Subscription::factory()->resource()->create();

        $this->from(route('resources.show', [$subscription->subscribable->id]))
            ->deleteSubscription($subscription, User::factory()->create())
            ->assertStatus(403);

        $this->assertModelExists($subscription);
    }
}
