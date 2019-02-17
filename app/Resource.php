<?php

namespace Hydrofon;

use Hydrofon\Scopes\GroupPolicyScope;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
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
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new GroupPolicyScope);
    }

    /**
     * Bookings of resource.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings()
    {
        return $this->hasMany(\Hydrofon\Booking::class);
    }

    /**
     * Categories the resource belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(\Hydrofon\Category::class)
                    ->orderBy('name');
    }

    /**
     * Buckets the resource belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function buckets()
    {
        return $this->belongsToMany(\Hydrofon\Bucket::class);
    }

    /**
     * Groups the resource belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(\Hydrofon\Group::class);
    }
}
