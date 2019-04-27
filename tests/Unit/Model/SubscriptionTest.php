<?php

namespace Tests\Unit\Model;

use Hydrofon\Booking;
use Hydrofon\Resource;
use Hydrofon\Subscription;
use Hydrofon\User;
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

    /**
     * A user subscription can be rendered.
     *
     * @return void
     */
    public function testUserSubscriptionIsRendered()
    {
        $user         = factory(User::class)->create();
        $subscription = factory(Subscription::class)->create([
            'subscribable_type' => '\Hydrofon\User',
            'subscribable_id'   => $user->id,
        ]);

        $rendered = $subscription->toCalendar();

        $this->assertIsString($rendered);
        $this->assertContains('VCALENDAR', $rendered);
        $this->assertContains($user->name, $rendered);
    }

    /**
     * A resource subscription can be rendered.
     *
     * @return void
     */
    public function testResourceSubscriptionIsRendered()
    {
        $resource     = factory(Resource::class)->create();
        $subscription = factory(Subscription::class)->create([
            'subscribable_type' => '\Hydrofon\Resource',
            'subscribable_id'   => $resource->id,
        ]);

        $rendered = $subscription->toCalendar();

        $this->assertIsString($rendered);
        $this->assertContains('VCALENDAR', $rendered);
        $this->assertContains($resource->name, $rendered);
    }

    /**
     * Bookings are included as events.
     *
     * @return void
     */
    public function testBookingsAreIncludedAsEvents()
    {
        $subscription = factory(Subscription::class)->create([
            'subscribable_type' => '\Hydrofon\User',
            'subscribable_id'   => factory(User::class)->create()->id,
        ]);
        $subscription->subscribable->bookings()->save($booking = factory(Booking::class)->create());

        $rendered = $subscription->toCalendar();

        $this->assertIsString($rendered);
        $this->assertContains('VEVENT', $rendered);
        $this->assertContains($booking->resource->name, $rendered);
    }

    /**
     * Bookings with the same start time is bundled in the same event.
     *
     * @return void
     */
    public function testBookingsAreBundled()
    {
        $subscription = factory(Subscription::class)->create([
            'subscribable_type' => '\Hydrofon\User',
            'subscribable_id'   => factory(User::class)->create()->id,
        ]);
        $subscription->subscribable->bookings()->saveMany(factory(Booking::class, 2)->create([
            'start_time' => now(),
            'end_time'   => now()->addHour(),
        ]));

        $rendered = $subscription->toCalendar();

        $this->assertIsString($rendered);
        $this->assertContains('VEVENT', $rendered);
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

        $subscription = factory(Subscription::class)->create([
            'subscribable_type' => '\Hydrofon\User',
            'subscribable_id'   => factory(User::class)->create()->id,
        ]);
        $subscription->subscribable->bookings()->save(factory(Booking::class)->create($timestamps));
        $subscription->subscribable->bookings()->save(factory(Booking::class)->create(array_merge($timestamps, [
            'resource_id' => factory(Resource::class)->states('facility')->create()->id,
        ])));

        $rendered = $subscription->toCalendar();

        $this->assertIsString($rendered);
        $this->assertContains('VEVENT', $rendered);
        $this->assertEquals(2, substr_count($rendered, 'BEGIN:VEVENT'));
    }

    /**
     * Events in a facility resource subscription are summarized with name of user.
     *
     * @return void
     */
    public function testFacilityResourceSubscriptionHaveUserName()
    {
        $subscription = factory(Subscription::class)->create([
            'subscribable_type' => '\Hydrofon\User',
            'subscribable_id'   => factory(User::class)->create()->id,
        ]);
        $subscription->subscribable->bookings()->save($booking = factory(Booking::class)->create([
            'resource_id' => factory(Resource::class)->states('facility')->create()->id,
        ]));

        $rendered = $subscription->toCalendar();

        $this->assertContains('SUMMARY:'.$booking->user->name, $rendered);
    }
}
