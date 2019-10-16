<?php

namespace Hydrofon;

use Eluceo\iCal\Component\Calendar;
use Eluceo\iCal\Component\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subscription extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subscribable_id',
        'subscribable_type',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically assign a UUID when storing subscription.
        static::creating(function ($subscription) {
            $subscription->uuid = (string) Str::uuid();
        });
    }

    /**
     * Get all of the owning subscribable models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function subscribable()
    {
        return $this->morphTo();
    }

    /**
     * Render subscription as calendar.
     *
     * @return string
     */
    public function toCalendar()
    {
        $vCalendar = new Calendar('Hydrofon\\EN');
        $vCalendar->setName($this->subscribable->name.' | Hydrofon');

        $this->subscribable->load([
            'bookings',
            'bookings.resource',
            'bookings.user',
        ]);

        collect()
            ->merge($this->createFacilityEvents())
            ->merge($this->createResourceEvents())
            ->each(function ($item, $key) use ($vCalendar) {
                $vCalendar->addComponent($item);
            });

        return $vCalendar->render();
    }

    /**
     * Generate events for resource bookings.
     *
     * @return \Illuminate\Support\Collection
     */
    private function createResourceEvents()
    {
        return $this->subscribable->bookings
            ->filter(function ($item, $key) {
                return ! $item->resource->is_facility;
            })
            ->groupBy(function ($item, $key) {
                return $item->start_time->format('YmdHis');
            })
            ->map(function ($item, $key) {
                $vEvent = new Event();

                $vEvent
                    ->setDtStart($item->first()->start_time)
                    ->setDtEnd($item->first()->start_time->addHour())
                    ->setSummary('Pick up '.Str::plural('booking', $item->count()))
                    ->setDescription('To pick up:'.PHP_EOL.'- '.$item->implode('resource.name', PHP_EOL.'- '));

                return $vEvent;
            });
    }

    /**
     * Generate events for facility bookings.
     *
     * @return \Illuminate\Support\Collection
     */
    private function createFacilityEvents()
    {
        return $this->subscribable->bookings
            ->filter(function ($item, $key) {
                return $item->resource->is_facility;
            })
            ->map(function ($item, $key) {
                $vEvent = new Event();

                $summary = $this->subscribable instanceof User
                    ? $item->resource->name
                    : $item->user->name;

                $vEvent
                    ->setDtStart($item->start_time)
                    ->setDtEnd($item->end_time)
                    ->setSummary($summary)
                    ->setDescription('');

                return $vEvent;
            });
    }
}
