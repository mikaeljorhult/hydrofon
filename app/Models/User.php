<?php

namespace App\Models;

use App\Settings\General;
use App\States\CheckedOut;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Prunable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_admin' => 'boolean',
            'last_logged_in_at' => 'datetime',
        ];
    }

    /**
     * Get the prunable model query.
     */
    public function prunable(): Builder
    {
        return static::whereDoesntHave('bookings', function (Builder $query) {
            return $query->whereState('state', CheckedOut::class);
        })
            ->where(function ($query) {
                // User has never logged in and was created more than a year ago.
                $query->whereNull('last_logged_in_at')
                    ->whereDate('created_at', '<=', now()->subDays(app(General::class)->prune_users));
            })
            ->orWhere(function ($query) {
                // User has logged in but not been active for more than a year.
                $query->whereNotNull('last_logged_in_at')
                    ->whereDate('last_logged_in_at', '<=', now()->subDays(app(General::class)->prune_bookings));
            });
    }

    protected function pruning()
    {
        $this->bookings()->delete();
        $this->identifiers()->delete();
        $this->subscription()->delete();
    }

    /**
     * Whether user is administrator or not.
     */
    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }

    /**
     * User is being impersonated by another user.
     */
    public function isImpersonated(): bool
    {
        return session()->has('impersonate');
    }

    /**
     * Check if user owns another model.
     */
    public function owns($related): bool
    {
        return $this->id == $related->user_id;
    }

    /**
     * Groups the user can approve bookings for.
     */
    public function approvingGroups(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Group::class, 'approver_group');
    }

    /**
     * Get all identifiers.
     */
    public function identifiers(): MorphMany
    {
        return $this->morphMany(\App\Models\Identifier::class, 'identifiable');
    }

    /**
     * Bookings owned by user.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(\App\Models\Booking::class);
    }

    /**
     * Groups the user belongs to.
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Group::class);
    }

    /**
     * Subscription of user bookings.
     */
    public function subscription(): MorphOne
    {
        return $this->morphOne(\App\Models\Subscription::class, 'subscribable');
    }

    /**
     * Export user personal data to JSON.
     *
     * @return false|string
     */
    public function exportToJson()
    {
        $user = $this->load([
            'bookings.resource',
            'identifiers',
        ]);

        $user->bookings->transform(function ($item, $key) {
            return (object) [
                'resource' => $item->resource->name,
                'start' => $item->start_time->format('U'),
                'end' => $item->end_time->format('U'),
            ];
        });

        $user->identifiers->transform(function ($item, $key) {
            return $item->value;
        });

        return json_encode($user->only(['email', 'name', 'bookings', 'identifiers']));
    }
}
