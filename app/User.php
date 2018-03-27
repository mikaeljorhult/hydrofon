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

    /**
     * Scope to order query by a specific field.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $field
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByField($query, $field)
    {
        // Check which field to order by.
        if (in_array($field, ['name', 'email'])) {
            return $query->orderBy($field);
        } elseif (in_array($field, ['group'])) {
            // Pluralize to get table name.
            $plural = str_plural($field);

            // Join tables and order by relationship name.
            return $query
                ->join($plural, $plural.'.id', '=', 'users.'.$field.'_id')
                ->orderBy($plural.'.name');
        }

        return $query;
    }

    /**
     * Scope to allow filtering models based on request.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByRequest($query)
    {
        if (request()->has('filter')) {
            $query->where('email', 'LIKE', '%'.request()->get('filter').'%')
                  ->orWhere('name', 'LIKE', '%'.request()->get('filter').'%');
        }

        return $query;
    }
}
