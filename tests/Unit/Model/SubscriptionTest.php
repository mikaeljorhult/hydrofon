<?php

namespace Tests\Unit\Model;

use App\Models\Booking;
use App\Models\Resource;
use App\Models\Subscription;
use App\Models\User;
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
        $subscription = Subscription::factory()->create();

        $this->assertNotNull($subscription->uuid);
    }

    /**
     * A user subscription can be rendered.
     *
     * @return void
     */
    public function testUserSubscriptionIsRendered()
    {
        $user = User::factory()->create();
        $subscription = Subscription::factory()->create([
            'subscribable_type' => \App\Models\User::class,
            'subscribable_id'   => $user->id,
        ]);

        $rendered = $subscription->toCalendar();

        $this->assertIsString($rendered);
        $this->assertStringContainsString('VCALENDAR', $rendered);
        $this->assertStringContainsString($user->name, $rendered);
    }

    /**
     * A resource subscription can be rendered.
     *
     * @return void
     */
    public function testResourceSubscriptionIsRendered()
    {
        $resource = Resource::factory()->create();
        $subscription = Subscription::factory()->create([
            'subscribable_type' => \App\Models\Resource::class,
            'subscribable_id'   => $resource->id,
        ]);

        $rendered = $subscription->toCalendar();

        $this->assertIsString($rendered);
        $this->assertStringContainsString('VCALENDAR', $rendered);
        $this->assertStringContainsString($resource->name, $rendered);
    }

    /**
     * Bookings are included as events.
     *
     * @return void
     */
    public function testBookingsAreIncludedAsEvents()
    {
        $subscription = Subscription::factory()->create([
            'subscribable_type' => \App\Models\User::class,
            'subscribable_id'   => User::factory()->create()->id,
        ]);
        $subscription->subscribable->bookings()->save($booking = Booking::factory()->create());

        $rendered = $subscription->toCalendar();

        $this->assertIsString($rendered);
        $this->assertStringContainsString('VEVENT', $rendered);
        $this->assertStringContainsString($booking->resource->name, $rendered);
    }

    /**
     * Bookings with the same start time is bundled in the same event.
     *
     * @return void
     */
    public function testBookingsAreBundled()
    {
        $subscription = Subscription::factory()->create([
            'subscribable_type' => \App\Models\User::class,
            'subscribable_id'   => User::factory()->create()->id,
        ]);
        $subscription->subscribable->bookings()->saveMany(Booking::factory()->times(2)->create([
            'start_time' => now(),
            'end_time'   => now()->addHour(),
        ]));

        $rendered = $subscription->toCalendar();

        $this->assertIsString($rendered);
        $this->assertStringContainsString('VEVENT', $rendered);
        $this->assertEquals(1, substr_count($rendered, 'BEGIN:VEVENT'));
    }

    /**
     * Bookings of resources are bundled while facility bookings are not.
     *
     * @return void
     */
    public function testFacilityBookingsAreNotBundled()
    {
        $timestamps = [
            'start_time' => today(),
            'end_time'   => today()->addHour(),
        ];

        $subscription = Subscription::factory()->create([
            'subscribable_type' => \App\Models\User::class,
            'subscribable_id'   => User::factory()->create()->id,
        ]);
        $subscription->subscribable->bookings()->save(Booking::factory()->create($timestamps));
        $subscription->subscribable->bookings()->save(Booking::factory()->create(array_merge($timestamps, [
            'resource_id' => Resource::factory()->facility()->create()->id,
        ])));

        $rendered = $subscription->toCalendar();

        $this->assertIsString($rendered);
        $this->assertStringContainsString('VEVENT', $rendered);
        $this->assertEquals(2, substr_count($rendered, 'BEGIN:VEVENT'));
    }

    /**
     * Events in a facility resource subscription are summarized with name of user.
     *
     * @return void
     */
    public function testFacilityResourceSubscriptionHaveUserName()
    {
        $subscription = Subscription::factory()->create([
            'subscribable_type' => \App\Models\Resource::class,
            'subscribable_id'   => Resource::factory()->facility()->create()->id,
        ]);
        $subscription->subscribable->bookings()->save($booking = Booking::factory()->create());

        $rendered = $subscription->toCalendar();

        $this->assertStringContainsString('SUMMARY:'.$booking->user->name, $rendered);
    }
}
