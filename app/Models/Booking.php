<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStatus\HasStatuses;

class Booking extends Model
{
    use HasFactory, HasStatuses;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'resource_id',
        'start_time',
        'end_time',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'start_time' => 'datetime:Y-m-d H:i',
        'end_time'   => 'datetime:Y-m-d H:i',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($booking) {
            $booking->created_by_id = session()->has('impersonate')
                ? session()->get('impersonated_by')
                : auth()->id();
        });

        static::created(function ($booking) {
            $mustBeApproved = $booking->user->groups()->whereHas('approvers')->exists();

            if ($mustBeApproved) {
                $booking->setStatus('pending');
            } else {
                $booking->setStatus('approved', 'Automatically approved');
            }
        });
    }

    /**
     * User that created the booking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function created_by()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * The booked resource.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resource()
    {
        return $this->belongsTo(\App\Models\Resource::class);
    }

    /**
     * User owning the booking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Booking can be checked in.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function checkin()
    {
        return $this->hasOne(\App\Models\Checkin::class);
    }

    /**
     * Booking can be checked out.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function checkout()
    {
        return $this->hasOne(\App\Models\Checkout::class);
    }

    /**
     * Booking can be approved.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function approval()
    {
        return $this->hasOne(\App\Models\Approval::class);
    }

    /**
     * Scope a query to only include bookings between dates.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $start
     * @param  mixed  $end
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBetween($query, $start, $end)
    {
        // Make sure dates are Carbon objects.
        $start = Carbon::parse((is_numeric($start) ? '@' : '').$start);
        $end = Carbon::parse((is_numeric($end) ? '@' : '').$end);

        // Add second and subtract second to allow booking to start or end at same time.
        $startTime = $start->copy()->addSecond();
        $endTime = $end->copy()->subSecond();

        // Return any bookings with the same resource and within the same time frame.
        return $query->where(function ($query) use ($startTime, $endTime) {
            $query
                // Exactly the same time as given interval.
                ->where([
                    ['start_time', $startTime->copy()->subSecond()],
                    ['end_time', $endTime->copy()->addSecond()],
                ])
                // Start before and end after interval.
                ->orWhere([
                    ['start_time', '<', $startTime],
                    ['end_time', '>', $endTime],
                ])
                // Start in interval.
                ->orWhereBetween('start_time', [$startTime, $endTime])
                // End in interval.
                ->orWhereBetween('end_time', [$startTime, $endTime]);
        });
    }

    /**
     * Scope a query to only include bookings in the past.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrent($query)
    {
        return $query->where('start_time', '<=', now())
                     ->where('end_time', '>=', now());
    }

    /**
     * Scope a query to only include bookings in the past.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePast($query)
    {
        return $query->where('end_time', '<=', now());
    }

    /**
     * Scope a query to only include bookings in the future.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFuture($query)
    {
        return $query->where('start_time', '>=', now());
    }

    /**
     * Scope a query to only include bookings that are to be returned.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOverdue($query)
    {
        return $query
            ->whereHas('resource', function ($query) {
                $query->where('is_facility', '=', 0);
            })
            ->past()
            ->has('checkout')
            ->doesntHave('checkin');
    }

    /**
     * Calculate duration in seconds.
     *
     * @return int
     */
    public function getDurationAttribute()
    {
        return $this->start_time->diffInSeconds($this->end_time);
    }
}
