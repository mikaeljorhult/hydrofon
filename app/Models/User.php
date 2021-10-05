<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_admin'          => 'boolean',
        'last_logged_in_at' => 'datetime',
    ];

    /**
     * Whether user is administrator or not.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->is_admin;
    }

    /**
     * User is being impersonated by another user.
     *
     * @return bool
     */
    public function isImpersonated()
    {
        return session()->has('impersonate');
    }

    /**
     * Check if user owns another model.
     *
     * @return bool
     */
    public function owns($related)
    {
        return $this->id == $related->user_id;
    }

    /**
     * Groups the user can approve bookings for.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function approvingGroups()
    {
        return $this->belongsToMany(\App\Models\Group::class, 'approver_group');
    }

    /**
     * Get all identifiers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function identifiers()
    {
        return $this->morphMany(\App\Models\Identifier::class, 'identifiable');
    }

    /**
     * Bookings owned by user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings()
    {
        return $this->hasMany(\App\Models\Booking::class);
    }

    /**
     * Groups the user belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(\App\Models\Group::class);
    }

    /**
     * Subscription of user bookings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function subscription()
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
                'start'    => $item->start_time->format('U'),
                'end'      => $item->end_time->format('U'),
            ];
        });

        $user->identifiers->transform(function ($item, $key) {
            return $item->value;
        });

        return json_encode($user->only(['email', 'name', 'bookings', 'identifiers']));
    }
}
