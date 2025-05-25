<?php

namespace App\Models;

use App\Enums\ApprovalSetting;
use App\Settings\General;
use App\States\Approved;
use App\States\AutoApproved;
use App\States\BookingState;
use App\States\CheckedIn;
use App\States\CheckedOut;
use App\States\Completed;
use App\States\Pending;
use App\States\Rejected;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\ModelStates\HasStates;

class Booking extends Model
{
    use HasFactory, HasStates, LogsActivity, Prunable;

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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_time' => 'datetime:Y-m-d H:i',
            'end_time' => 'datetime:Y-m-d H:i',
            'state' => BookingState::class,
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($booking) {
            $booking->created_by_id = session()->has('impersonate')
                ? session()->get('impersonated_by')
                : auth()->id();
        });

        static::created(function ($booking) {
            $requireApproval = app(General::class)->require_approval;

            if (
                $requireApproval === ApprovalSetting::NONE->value
                || ($requireApproval === ApprovalSetting::EQUIPMENT->value && $booking->resource->isFacility())
                || ($requireApproval === ApprovalSetting::FACILITIES->value && ! $booking->resource->isFacility())
            ) {
                $mustBeApproved = false;
            } else {
                $mustBeApproved = $booking->user->groups()->whereHas('approvers')->exists();
            }

            // Booking needs to be approved before it can be checked out or used.
            if ($mustBeApproved) {
                $booking->state->transitionTo(Pending::class);

                return;
            }

            // No approval is needed and it will not be checked out so bookings is complete.
            if ($booking->resource->isFacility()) {
                $booking->state->transitionTo(Completed::class);

                return;
            }

            // Booking needs no approval and is cleared to be checked out.
            $booking->state->transitionTo(AutoApproved::class);
        });

        static::updating(function (Booking $booking) {
            // Bail if state is being transitioned.
            if ($booking->isDirty('state')) {
                return;
            }

            $requireApproval = app(General::class)->require_approval;

            if (
                (auth()->user() && auth()->user()->isAdmin())
                || $requireApproval === ApprovalSetting::NONE->value
                || ($requireApproval === ApprovalSetting::EQUIPMENT->value && $booking->resource->isFacility())
                || ($requireApproval === ApprovalSetting::FACILITIES->value && ! $booking->resource->isFacility())
            ) {
                return;
            }

            if ($booking->isApproved || $booking->isRejected) {
                $mustBeApproved = $booking->user->groups()->whereHas('approvers')->exists();

                if ($mustBeApproved) {
                    $booking->state->transitionTo(Pending::class);
                }
            }
        });
    }

    /**
     * Get the prunable model query.
     */
    public function prunable(): Builder
    {
        // Booking is not checked out and ended at least half a year ago.
        return static::whereNotState('state', CheckedOut::class)
            ->where('end_time', '<=', now()->subDays(app(General::class)->prune_bookings));
    }

    /**
     * User that created the booking.
     */
    public function created_by(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * The booked resource.
     */
    public function resource(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Resource::class);
    }

    /**
     * User owning the booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Get approved state.
     */
    protected function getIsApprovedAttribute(): bool
    {
        return $this->state->equals(Approved::class)
            || $this->state->equals(AutoApproved::class);
    }

    /**
     * Get rejected state.
     */
    protected function getIsRejectedAttribute(): bool
    {
        return $this->state->equals(Rejected::class);
    }

    /**
     * Get pending state.
     */
    protected function getIsPendingAttribute(): bool
    {
        return $this->state->equals(Pending::class);
    }

    /**
     * Get checked in state.
     */
    protected function getIsCheckedInAttribute(): bool
    {
        return $this->state->equals(CheckedIn::class);
    }

    /**
     * Get checked out state.
     */
    protected function getIsCheckedOutAttribute(): bool
    {
        return $this->state->equals(CheckedOut::class);
    }

    /**
     * Scope a query to only include bookings between dates.
     *
     * @param  mixed  $start
     * @param  mixed  $end
     */
    public function scopeBetween(Builder $query, $start, $end): Builder
    {
        // Make sure dates are Carbon objects.
        $start = is_numeric($start) ? Carbon::createFromTimestamp($start) : Carbon::parse($start);
        $end = is_numeric($end) ? Carbon::createFromTimestamp($end) : Carbon::parse($end);

        // Add second and subtract second to allow booking to start or end at same time.
        $startTime = $start->copy()->addSecond();
        $endTime = $end->copy()->subSecond();

        // Return any bookings with the same resource and within the same time frame.
        return $query->where(function ($query) use ($startTime, $endTime) {
            $query
                // Exactly the same time as given interval.
                ->where(function ($query) use ($startTime, $endTime) {
                    $query
                        ->where('start_time', '=', $startTime->copy()->subSecond())
                        ->where('end_time', '=', $endTime->copy()->addSecond());
                })
                // Start before and end after interval.
                ->orWhere(function ($query) use ($startTime, $endTime) {
                    $query
                        ->where('start_time', '<', $startTime)
                        ->where('end_time', '>', $endTime);
                })
                // Start in interval.
                ->orWhereBetween('start_time', [$startTime, $endTime])
                // End in interval.
                ->orWhereBetween('end_time', [$startTime, $endTime]);
        });
    }

    /**
     * Scope a query to only include bookings in the past.
     */
    public function scopeCurrent(Builder $query): Builder
    {
        return $query->where('start_time', '<=', now())
            ->where('end_time', '>=', now());
    }

    /**
     * Scope a query to only include bookings in the past.
     */
    public function scopePast(Builder $query): Builder
    {
        return $query->where('end_time', '<=', now());
    }

    /**
     * Scope a query to only include bookings in the future.
     */
    public function scopeFuture(Builder $query): Builder
    {
        return $query->where('start_time', '>=', now());
    }

    /**
     * Scope a query to only include bookings that are to be returned.
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query
            ->whereHas('resource', function ($query) {
                $query->where('is_facility', '=', 0);
            })
            ->past()
            ->whereState('state', CheckedOut::class);
    }

    /**
     * Scope a query to only include approved bookings.
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->whereState('state', [Approved::class, AutoApproved::class]);
    }

    /**
     * Scope a query to only include rejected bookings.
     */
    public function scopeRejected(Builder $query): Builder
    {
        return $query->whereState('state', Rejected::class);
    }

    /**
     * Scope a query to only include rejected bookings.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->whereState('state', Pending::class);
    }

    /**
     * Calculate duration in seconds.
     */
    public function getDurationAttribute(): int
    {
        return $this->start_time->diffInSeconds($this->end_time);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'user_id',
                'resource_id',
                'start_time',
                'end_time',
            ])
            ->logOnlyDirty();
    }

    /**
     * Approve booking.
     */
    public function approve(): void
    {
        if (auth()->user()->cannot('approve', $this)) {
            abort(403);
        }

        $this->state->transitionTo(Approved::class);
    }

    /**
     * Reject booking.
     */
    public function reject(): void
    {
        if (auth()->user()->cannot('approve', $this)) {
            abort(403);
        }

        $this->state->transitionTo(Rejected::class);
    }

    /**
     * Revoke booking approval.
     */
    public function revoke(): void
    {
        if (auth()->user()->cannot('approve', $this)) {
            abort(403);
        }

        $this->state->transitionTo(Pending::class);
    }
}
