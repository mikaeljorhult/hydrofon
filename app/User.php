<?php

namespace Hydrofon;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
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
        'is_admin' => 'boolean',
    ];

    /**
     * Whether user is administrator or not.
     *
     * @return boolean
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
     * @return boolean
     */
    public function owns($related)
    {
        return $this->id == $related->user_id;
    }

    /**
     * Get all identifiers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function identifiers()
    {
        return $this->morphMany(\Hydrofon\Identifier::class, 'identifiable');
    }

    /**
     * Bookings owned by user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings()
    {
        return $this->hasMany(\Hydrofon\Booking::class);
    }

    /**
     * Groups the user belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(\Hydrofon\Group::class);
    }
}
