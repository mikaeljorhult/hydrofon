<?php

namespace Tests\Feature\Subscriptions;

use Hydrofon\Resource;
use Hydrofon\Subscription;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Posts request to delete a subscription.
     *
     * @param  \Hydrofon\Subscription  $subscription
     * @param  \Hydrofon\User|null  $user
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function deleteSubscription($subscription, $user = null)
    {
        return $this->actingAs($user ?: factory(User::class)->states('admin')->create())
                    ->delete('subscriptions/'.$subscription->id);
    }

    /**
     * A user can delete subscription of their own bookings.
     *
     * @return void
     */
    public function testUserSubscriptionsCanBeDeleted()
    {
        $user         = factory(User::class)->create();
        $subscription = factory(Subscription::class)->create([
            'subscribable_type' => '\Hydrofon\User',
            'subscribable_id'   => $user->id,
        ]);

        $this->from(route('users.show', [$user->id]))
             ->deleteSubscription($subscription, $user)
             ->assertRedirect(route('users.show', [$user->id]));

        $this->assertDatabaseMissing('subscriptions', [
            'subscribable_type' => '\Hydrofon\User',
            'subscribable_id'   => $user->id,
        ]);
    }

    /**
     * An administrator can delete subscription of another users bookings.
     *
     * @return void
     */
    public function testAdministratorCanDeleteAnyUserSubscription()
    {
        $user         = factory(User::class)->create();
        $subscription = factory(Subscription::class)->create([
            'subscribable_type' => '\Hydrofon\User',
            'subscribable_id'   => $user->id,
        ]);

        $this->from(route('users.show', [$user->id]))
             ->deleteSubscription($subscription)
             ->assertRedirect(route('users.show', [$user->id]));

        $this->assertDatabaseMissing('subscriptions', [
            'subscribable_type' => '\Hydrofon\User',
            'subscribable_id'   => $user->id,
        ]);
    }

    /**
     * An administrator can delete subscription of resource bookings.
     *
     * @return void
     */
    public function testAdministratorCanDeleteAResourceSubscription()
    {
        $resource     = factory(Resource::class)->create();
        $subscription = factory(Subscription::class)->create([
            'subscribable_type' => '\Hydrofon\Resource',
            'subscribable_id'   => $resource->id,
        ]);

        $this->from(route('resources.show', [$resource->id]))
             ->deleteSubscription($subscription)
             ->assertRedirect(route('resources.show', [$resource->id]));

        $this->assertDatabaseMissing('subscriptions', [
            'subscribable_type' => '\Hydrofon\Resource',
            'subscribable_id'   => $resource->id,
        ]);
    }

    /**
     * A regular user can not delete subscription of resource bookings.
     *
     * @return void
     */
    public function testUserCanNotDeleteAResourceSubscription()
    {
        $resource     = factory(Resource::class)->create();
        $subscription = factory(Subscription::class)->create([
            'subscribable_type' => '\Hydrofon\Resource',
            'subscribable_id'   => $resource->id,
        ]);

        $this->from(route('resources.show', [$resource->id]))
             ->deleteSubscription($subscription, factory(User::class)->create())
             ->assertStatus(403);

        $this->assertDatabaseHas('subscriptions', [
            'subscribable_type' => '\Hydrofon\Resource',
            'subscribable_id'   => $resource->id,
        ]);
    }
}
