<?php

namespace Tests\Unit\Model;

use App\Models\Booking;
use App\Models\Resource;
use App\Models\Subscription;
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
        $subscription = Subscription::factory()->user()->create();

        $rendered = $subscription->toCalendar();

        $this->assertIsString($rendered);
        $this->assertStringContainsString('VCALENDAR', $rendered);
        $this->assertStringContainsString($subscription->subscribable->name, $rendered);
    }

    /**
     * A resource subscription can be rendered.
     *
     * @return void
     */
    public function testResourceSubscriptionIsRendered()
    {
        $subscription = Subscription::factory()->resource()->create();

        $rendered = $subscription->toCalendar();

        $this->assertIsString($rendered);
        $this->assertStringContainsString('VCALENDAR', $rendered);
        $this->assertStringContainsString($subscription->subscribable->name, $rendered);
    }

    /**
     * Bookings are included as events.
     *
     * @return void
     */
    public function testBookingsAreIncludedAsEvents()
    {
        $subscription = Subscription::factory()->user()->create();
        $booking = Booking::factory()->for($subscription->subscribable)->create();

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
        $subscription = Subscription::factory()->user()->create();
        Booking::factory()->times(2)->for($subscription->subscribable)->create([
            'start_time' => now(),
            'end_time'   => now()->addHour(),
        ]);

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

        $subscription = Subscription::factory()->user()->create();

        Booking::factory()->for($subscription->subscribable)->create($timestamps);
        Booking::factory()->for($subscription->subscribable)->for(Resource::factory()->facility())->create($timestamps);

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
        $booking = Booking::factory()->create([
            'resource_id' => Resource::factory()->facility(),
        ]);

        $subscription = Subscription::factory()->create([
            'subscribable_type' => \App\Models\Resource::class,
            'subscribable_id'   => $booking->resource->id,
        ]);

        $rendered = $subscription->toCalendar();

        $this->assertStringContainsString('SUMMARY:'.$booking->user->name, $rendered);
    }
}
