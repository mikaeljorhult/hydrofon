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
     */
    public function testUuidIsCreated(): void
    {
        $subscription = Subscription::factory()->create();

        $this->assertNotNull($subscription->uuid);
    }

    /**
     * A user subscription can be rendered.
     */
    public function testUserSubscriptionIsRendered(): void
    {
        $subscription = Subscription::factory()->user()->create();

        $rendered = $subscription->toCalendar();

        $this->assertIsString($rendered);
        $this->assertStringContainsString('VCALENDAR', $rendered);
        $this->assertStringContainsString($subscription->subscribable->name, $rendered);
    }

    /**
     * A resource subscription can be rendered.
     */
    public function testResourceSubscriptionIsRendered(): void
    {
        $subscription = Subscription::factory()->resource()->create();

        $rendered = $subscription->toCalendar();

        $this->assertIsString($rendered);
        $this->assertStringContainsString('VCALENDAR', $rendered);
        $this->assertStringContainsString($subscription->subscribable->name, $rendered);
    }

    /**
     * Bookings are included as events.
     */
    public function testBookingsAreIncludedAsEvents(): void
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
     */
    public function testBookingsAreBundled(): void
    {
        $subscription = Subscription::factory()->user()->create();
        Booking::factory()->times(2)->for($subscription->subscribable)->create([
            'start_time' => now(),
            'end_time' => now()->addHour(),
        ]);

        $rendered = $subscription->toCalendar();

        $this->assertIsString($rendered);
        $this->assertStringContainsString('VEVENT', $rendered);
        $this->assertEquals(1, substr_count($rendered, 'BEGIN:VEVENT'));
    }

    /**
     * Bookings of resources are bundled while facility bookings are not.
     */
    public function testFacilityBookingsAreNotBundled(): void
    {
        $timestamps = [
            'start_time' => today(),
            'end_time' => today()->addHour(),
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
     */
    public function testFacilityResourceSubscriptionHaveUserName(): void
    {
        $booking = Booking::factory()->create([
            'resource_id' => Resource::factory()->facility(),
        ]);

        $subscription = Subscription::factory()->create([
            'subscribable_type' => \App\Models\Resource::class,
            'subscribable_id' => $booking->resource->id,
        ]);

        $rendered = $subscription->toCalendar();

        $this->assertStringContainsString('SUMMARY:'.$booking->user->name, $rendered);
    }
}
