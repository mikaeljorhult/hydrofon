<?php

namespace App\Reports;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class Utilization
{
    /**
     * Resources to calculate.
     *
     * @var array
     */
    private $resources;

    /**
     * Start of time span to check.
     *
     * @var \Carbon\Carbon
     */
    private $periodStart;

    /**
     * End of time span to check.
     *
     * @var \Carbon\Carbon
     */
    private $periodEnd;

    /**
     * Time when day starts.
     *
     * @var string
     */
    private $dayStart = '00:00';

    /**
     * Time when day starts.
     *
     * @var string
     */
    private $dayEnd = '24:00';

    /**
     * Class constructor.
     *
     * @param $resources
     * @param  \Carbon\Carbon  $periodStart
     * @param  \Carbon\Carbon  $periodEnd
     */
    public function __construct($resources, Carbon $periodStart, Carbon $periodEnd)
    {
        $this->resources = Arr::wrap($resources);
        $this->periodStart = $periodStart;
        $this->periodEnd = $periodEnd;
    }

    /**
     * Set times between which utilization should be calculated.
     *
     * @param  string  $start
     * @param  string  $end
     * @return void
     */
    public function setTime(string $start, string $end): void
    {
        $this->dayStart = $start;
        $this->dayEnd = $end;
    }

    /**
     * Calculate utilization for resources during supplied period.
     *
     * @param  \Carbon\Carbon  $startTime
     * @param  \Carbon\Carbon  $endTime
     * @return float|int
     */
    private function calculateUtilization(Carbon $startTime, Carbon $endTime)
    {
        $bookings = $this
            ->fetchBookings($this->resources, $startTime, $endTime)
            ->map(function ($booking) use ($startTime, $endTime) {
                if ($booking->start_time->lt($startTime)) {
                    $booking->start_time = $startTime;
                }

                if ($booking->end_time->gt($endTime)) {
                    $booking->end_time = $endTime;
                }

                return $booking;
            });

        $potentialHours = $this->getPotentialHours($this->resources, $startTime, $endTime);

        $reservedHours = $bookings->sum(function ($booking) {
            return $booking->start_time->diffInMinutes($booking->end_time);
        });

        return $reservedHours / $potentialHours;
    }

    /**
     * Get all bookings for the supplied resources and period.
     *
     * @param  array  $resources
     * @param  \Carbon\Carbon  $startTime
     * @param  \Carbon\Carbon  $endTime
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function fetchBookings(array $resources, Carbon $startTime, Carbon $endTime): Collection
    {
        return Booking::whereIn('resource_id', $resources)
                      ->between($startTime, $endTime)
                      ->get();
    }

    /**
     * Get collection of all days during period.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getDays(): Collection
    {
        return collect($this->periodStart->toPeriod($this->periodEnd));
    }

    public function calculateUtilizationForPeriod()
    {
        $days = $this->getDays();

        return $days->sum(function ($day) {
            return $this->calculateUtilization(
                $day->copy()->setTimeFromTimeString($this->dayStart),
                $day->copy()->setTimeFromTimeString($this->dayEnd)
            );
        }) / $days->count();
    }

    /**
     * @param  array  $resources
     * @param  \Carbon\Carbon  $startTime
     * @param  \Carbon\Carbon  $endTime
     * @return float|int
     */
    private function getPotentialHours(array $resources, Carbon $startTime, Carbon $endTime)
    {
        return $startTime->diffInMinutes($endTime) * count($resources);
    }
}
