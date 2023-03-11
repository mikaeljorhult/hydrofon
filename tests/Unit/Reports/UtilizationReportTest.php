<?php

namespace Tests\Unit\Reports;

use App\Models\Booking;
use App\Models\Resource;
use App\Reports\Utilization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UtilizationReportTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Utilization is calculated for a single resource.
     *
     * @return void
     */
    public function testUtilizationIsCalculatedForSingleResource(): void
    {
        $booking = Booking::factory()->create([
            'start_time' => now()->setTimeFromTimeString('00:00:00'),
            'end_time' => now()->setTimeFromTimeString('06:00:00'),
        ]);

        $utilization = new Utilization($booking->resource_id, now()->startOfDay(), now()->endOfDay());

        $ratio = $utilization->calculateUtilizationForPeriod();

        $this->assertTrue($ratio == 0.25);
    }

    /**
     * Utilization is calculated for multiple resources.
     *
     * @return void
     */
    public function testUtilizationIsCalculatedForMultipleResources(): void
    {
        $resources = Resource::factory()->count(2)->create();

        Booking::factory()->create([
            'resource_id' => $resources->first()->id,
            'start_time' => now()->setTimeFromTimeString('00:00:00'),
            'end_time' => now()->setTimeFromTimeString('06:00:00'),
        ]);

        $utilization = new Utilization($resources->modelKeys(), now()->startOfDay(), now()->endOfDay());

        $ratio = $utilization->calculateUtilizationForPeriod();

        $this->assertTrue($ratio == 0.125);
    }

    /**
     * Utilization is calculated for resource without bookings.
     *
     * @return void
     */
    public function testUtilizationIsCalculatedForResourceWithoutBookings(): void
    {
        $utilization = new Utilization(Resource::factory()->create()->id, now()->startOfDay(), now()->endOfDay());

        $ratio = $utilization->calculateUtilizationForPeriod();

        $this->assertTrue($ratio == 0);
    }

    /**
     * Bookings partly within scope are counted.
     *
     * @return void
     */
    public function testEarlyBookingsPartlyWithinScopeAreCounted(): void
    {
        $booking = Booking::factory()->create([
            'start_time' => now()->subDay()->setTimeFromTimeString('00:00:00'),
            'end_time' => now()->setTimeFromTimeString('06:00:00'),
        ]);

        $utilization = new Utilization($booking->resource_id, now()->startOfDay(), now()->endOfDay());

        $ratio = $utilization->calculateUtilizationForPeriod();

        $this->assertTrue($ratio == 0.25);
    }

    /**
     * Bookings partly within scope are counted.
     *
     * @return void
     */
    public function testLateBookingsPartlyWithinScopeAreCounted(): void
    {
        $booking = Booking::factory()->create([
            'start_time' => now()->setTimeFromTimeString('18:00:00'),
            'end_time' => now()->addDay()->setTimeFromTimeString('01:00:00'),
        ]);

        $utilization = new Utilization($booking->resource_id, now()->startOfDay(), now()->endOfDay());

        $ratio = $utilization->calculateUtilizationForPeriod();

        $this->assertTrue($ratio == 0.25);
    }

    /**
     * Period of multiple days are calculated.
     *
     * @return void
     */
    public function testUtilizationIsCalculatedOverMultipleDays(): void
    {
        Booking::factory()
               ->for($resource = Resource::factory()->create())
               ->createMany([
                   [
                       'start_time' => now()->setTimeFromTimeString('00:00:00'),
                       'end_time' => now()->setTimeFromTimeString('12:00:00'),
                   ],
                   [
                       'start_time' => now()->addDay()->setTimeFromTimeString('00:00:00'),
                       'end_time' => now()->addDay()->setTimeFromTimeString('12:00:00'),
                   ],
               ]);

        $utilization = new Utilization($resource->id, now()->startOfDay(), now()->endOfDay()->addDay());

        $ratio = $utilization->calculateUtilizationForPeriod();

        $this->assertTrue($ratio == 0.5);
    }

    /**
     * Setting a time range limit daily calculation.
     *
     * @return void
     */
    public function testSetTimeLimitCalculation(): void
    {
        $booking = Booking::factory()->create([
            'start_time' => now()->setTimeFromTimeString('08:00:00'),
            'end_time' => now()->setTimeFromTimeString('12:00:00'),
        ]);

        $utilization = new Utilization($booking->resource_id, now()->startOfDay(), now()->endOfDay());
        $utilization->setTime('08:00', '16:00');

        $ratio = $utilization->calculateUtilizationForPeriod();

        $this->assertTrue($ratio == 0.5);
    }

    /**
     * Setting a time range limit daily calculation for each day.
     *
     * @return void
     */
    public function testSetTimeLimitCalculationOverMultipleDays(): void
    {
        $booking = Booking::factory()->create([
            'start_time' => now()->setTimeFromTimeString('08:00:00'),
            'end_time' => now()->setTimeFromTimeString('12:00:00'),
        ]);

        $utilization = new Utilization($booking->resource_id, now()->startOfDay(), now()->addDay()->endOfDay());
        $utilization->setTime('08:00', '16:00');

        $ratio = $utilization->calculateUtilizationForPeriod();

        $this->assertTrue($ratio == 0.25);
    }

    /**
     * Bookings outside daily time range is excluded.
     *
     * @return void
     */
    public function testSetTimeCanExcludeBookings(): void
    {
        $booking = Booking::factory()->create([
            'start_time' => now()->setTimeFromTimeString('00:00:00'),
            'end_time' => now()->setTimeFromTimeString('06:00:00'),
        ]);

        $utilization = new Utilization($booking->resource_id, now()->startOfDay(), now()->endOfDay());
        $utilization->setTime('08:00', '16:00');

        $ratio = $utilization->calculateUtilizationForPeriod();

        $this->assertTrue($ratio == 0);
    }
}
