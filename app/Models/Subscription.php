<?php

namespace App\Models;

use Eluceo\iCal\Domain\Entity\Calendar;
use Eluceo\iCal\Domain\Entity\Event;
use Eluceo\iCal\Domain\ValueObject\DateTime;
use Eluceo\iCal\Domain\ValueObject\TimeSpan;
use Eluceo\iCal\Presentation\Component\Property;
use Eluceo\iCal\Presentation\Component\Property\Value\TextValue;
use Eluceo\iCal\Presentation\Factory\CalendarFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Subscription extends Model
{
    use HasFactory;

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
     */
    protected static function boot(): void
    {
        parent::boot();

        // Automatically assign a UUID when storing subscription.
        static::creating(function ($subscription) {
            $subscription->uuid = (string) Str::uuid();
        });
    }

    /**
     * Get all of the owning subscribable models.
     */
    public function subscribable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Render subscription as calendar.
     */
    public function toCalendar(): string
    {
        $this->subscribable->load([
            'bookings',
            'bookings.resource',
            'bookings.user',
        ]);

        $events = collect()
            ->merge($this->createFacilityEvents())
            ->merge($this->createResourceEvents())
            ->toArray();

        $calendar = new Calendar($events);
        $componentFactory = new CalendarFactory();
        $component = $componentFactory
            ->createCalendar($calendar)
            ->withProperty(new Property(
                'NAME',
                new TextValue($this->subscribable->name.' | Hydrofon')
            ))
            ->withProperty(new Property(
                'X-WR-CALNAME',
                new TextValue($this->subscribable->name.' | Hydrofon')
            ));

        return (string) $component;
    }

    /**
     * Generate events for resource bookings.
     */
    private function createResourceEvents(): Collection
    {
        return $this->subscribable->bookings
            ->filter(function ($item, $key) {
                return ! $item->resource->is_facility;
            })
            ->groupBy(function ($item, $key) {
                return $item->start_time->format('YmdHis');
            })
            ->map(function ($item, $key) {
                return (new Event())
                    ->setSummary('Pick up '.Str::plural('booking', $item->count()))
                    ->setDescription('To pick up:'.PHP_EOL.'- '.$item->implode('resource.name', PHP_EOL.'- '))
                    ->setOccurrence(
                        new TimeSpan(
                            new DateTime($item->first()->start_time, false),
                            new DateTime($item->first()->start_time->addHour(), false)
                        )
                    );
            });
    }

    /**
     * Generate events for facility bookings.
     */
    private function createFacilityEvents(): Collection
    {
        return $this->subscribable->bookings
            ->filter(function ($item, $key) {
                return $item->resource->is_facility;
            })
            ->map(function ($item, $key) {
                $summary = $this->subscribable instanceof User
                    ? $item->resource->name
                    : $item->user->name;

                return (new Event())
                    ->setSummary($summary)
                    ->setDescription('')
                    ->setOccurrence(
                        new TimeSpan(
                            new DateTime($item->start_time, false),
                            new DateTime($item->end_time, false)
                        )
                    );
            });
    }
}
