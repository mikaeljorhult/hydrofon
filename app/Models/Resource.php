<?php

namespace App\Models;

use App\Scopes\GroupPolicyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\ModelStatus\HasStatuses;

class Resource extends Model
{
    use HasFactory, HasStatuses, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'is_facility',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_facility' => 'boolean',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new GroupPolicyScope());
    }

    /**
     * Whether user is administrator or not.
     *
     * @return bool
     */
    public function isFacility()
    {
        return $this->is_facility;
    }

    /**
     * Bookings of resource.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings()
    {
        return $this->hasMany(\App\Models\Booking::class);
    }

    /**
     * Categories the resource belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(\App\Models\Category::class)
                    ->orderBy('name');
    }

    /**
     * Buckets the resource belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function buckets()
    {
        return $this->belongsToMany(\App\Models\Bucket::class);
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
     * Groups the resource belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(\App\Models\Group::class);
    }

    /**
     * Get current flag object for resource.
     *
     * @return mixed|string[]|null
     */
    public function getFlagAttribute()
    {
        return Flag::whereAbbr($this->status)->first();
    }

    /**
     * Checks if status being set is valid.
     *
     * @param  string  $name
     * @param  string|null  $reason
     * @return bool
     */
    public function isValidStatus(string $name, ?string $reason = null): bool
    {
        return in_array($name, Flag::pluck('abbr')->all());
    }

    /**
     * Determines what to activity to log.
     *
     * @return \Spatie\Activitylog\LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
                         ->logOnly([
                             'name',
                             'description',
                             'is_facility',
                         ])
                         ->logOnlyDirty();
    }
}
