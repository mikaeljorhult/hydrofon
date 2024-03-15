<?php

namespace App\Models;

use App\Scopes\GroupPolicyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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
     */
    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope(new GroupPolicyScope());
    }

    /**
     * Whether user is administrator or not.
     */
    public function isFacility(): bool
    {
        return $this->is_facility;
    }

    /**
     * Bookings of resource.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(\App\Models\Booking::class);
    }

    /**
     * Categories the resource belongs to.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Category::class)
            ->orderBy('name');
    }

    /**
     * Buckets the resource belongs to.
     */
    public function buckets(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Bucket::class);
    }

    /**
     * Get all identifiers.
     */
    public function identifiers(): MorphMany
    {
        return $this->morphMany(\App\Models\Identifier::class, 'identifiable');
    }

    /**
     * Groups the resource belongs to.
     */
    public function groups(): BelongsToMany
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
     */
    public function isValidStatus(string $name, ?string $reason = null): bool
    {
        return in_array($name, Flag::pluck('abbr')->all());
    }

    /**
     * Determines what to activity to log.
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
