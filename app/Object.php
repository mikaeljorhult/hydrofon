<?php

namespace Hydrofon;

use Illuminate\Database\Eloquent\Model;

class Object extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'facility',
    ];

    /**
     * Bookings of object.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings()
    {
        return $this->hasMany(\Hydrofon\Booking::class);
    }

    /**
     * Categories the object belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(\Hydrofon\Category::class)
                    ->orderBy('name');
    }
}
