<?php

namespace Tests\Feature\Subscriptions;

use App\Models\Resource;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Posts request to persist a subscription.
     *
     * @param array               $overrides
     * @param \App\Models\User|null $user
     *
     * @return \Illuminate\Testing\TestResponse
     */
    public function storeSubscription($overrides = [], $user = null)
    {
        $subscription = Subscription::factory()->make($overrides);

        return $this->actingAs($user ?: User::factory()->admin()->create())
                    ->post('subscriptions', $subscription->toArray());
    }

    /**
     * A user can subscribe to its bookings.
     *
     * @return void
     */
    public function testUserSubscriptionsCanBeStored()
    {
        $user = User::factory()->create();

        $this->from(route('users.show', [$user->id]))
             ->storeSubscription([
                 'subscribable_type' => 'user',
                 'subscribable_id'   => $user->id,
             ], $user)
             ->assertRedirect(route('users.show', [$user->id]));

        $this->assertDatabaseHas('subscriptions', [
            'subscribable_type' => \App\Models\User::class,
            'subscribable_id'   => $user->id,
        ]);
    }

    /**
     * Only one subscription is created for each object.
     *
     * @return void
     */
    public function testOnlyOneSubscriptionIsCreatedForEachObject()
    {
        $subscription = Subscription::factory()->create($attributes = [
            'subscribable_type' => \App\Models\User::class,
            'subscribable_id'   => User::factory()->create()->id,
        ]);

        $this->storeSubscription([
            'subscribable_type' => 'user',
            'subscribable_id'   => $subscription->subscribable_id,
        ]);

        $this->assertDatabaseHas('subscriptions', $attributes);
        $this->assertCount(1, Subscription::all());
    }

    /**
     * A user can not subscribe to bookings of another user.
     *
     * @return void
     */
    public function testUserCanNotSubscribeToOtherUsersBookings()
    {
        $users = User::factory()->times(2)->create();

        $this->storeSubscription([
            'subscribable_type' => 'user',
            'subscribable_id'   => $users->first()->id,
        ], $users->last());

        $this->assertCount(0, Subscription::all());
    }

    /**
     * An administrator can subscribe to bookings of a resource.
     *
     * @return void
     */
    public function testResourceSubscriptionsCanBeStored()
    {
        $resource = Resource::factory()->create();

        $this->from(route('resources.show', [$resource->id]))
             ->storeSubscription([
                 'subscribable_type' => 'resource',
                 'subscribable_id'   => $resource->id,
             ])
             ->assertRedirect(route('resources.show', [$resource->id]));

        $this->assertDatabaseHas('subscriptions', [
            'subscribable_type' => \App\Models\Resource::class,
            'subscribable_id'   => $resource->id,
        ]);
    }

    /**
     * A regular user can not subscribe to bookings of a resource.
     *
     * @return void
     */
    public function testUserCanNotSubscribeResourceBookings()
    {
        $resource = Resource::factory()->create();

        $this->from(route('resources.show', [$resource->id]))
             ->storeSubscription([
                 'subscribable_type' => 'resource',
                 'subscribable_id'   => $resource->id,
             ], User::factory()->create())
             ->assertStatus(403);

        $this->assertCount(0, Subscription::all());
    }
}
