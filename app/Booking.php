<?php

namespace Hydrofon;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'object_id',
        'start_time',
        'end_time',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
    ];

    /**
     * User that created the booking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function created_by()
    {
        return $this->belongsTo(\Hydrofon\User::class);
    }

    /**
     * The booked object.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function object()
    {
        return $this->belongsTo(\Hydrofon\Object::class);
    }

    /**
     * User owning the booking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\Hydrofon\User::class);
    }

    /**
     * Booking can be checked in.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function checkin()
    {
        return $this->hasOne(\Hydrofon\Checkin::class);
    }

    /**
     * Booking can be checked out.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function checkout()
    {
        return $this->hasOne(\Hydrofon\Checkout::class);
    }

    /**
     * Scope a query to only include bookings between dates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Carbon\Carbon $start
     * @param \Carbon\Carbon $end
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBetween($query, Carbon $start, Carbon $end)
    {
        // Add second and subtract second to allow booking to start or end at same time.
        $startTime = $start->copy()->addSecond();
        $endTime   = $end->copy()->subSecond();

        // Return any bookings with the same object and within the same time frame.
        return $query->where(function ($query) use ($startTime, $endTime) {
            $query
                // Exactly the same time as given interval.
                ->where([
                    ['start_time', $startTime->copy()->subSecond()],
                    ['end_time', $endTime->copy()->addSecond()]
                ])
                // Start before and end after interval.
                ->orWhere([
                    ['start_time', '<', $startTime],
                    ['end_time', '>', $endTime]
                ])
                // Start in interval.
                ->orWhereBetween('start_time', [$startTime, $endTime])
                // End in interval.
                ->orWhereBetween('end_time', [$startTime, $endTime]);
        });
    }

    /**
     * Calculate duration in seconds.
     *
     * @return int
     */
    public function getDurationAttribute() {
        return $this->start_time->diffInSeconds($this->end_time);
    }
}
