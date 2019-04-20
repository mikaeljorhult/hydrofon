<?php

namespace Hydrofon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subscription extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subscribable_id',
        'subscribable_type',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically assign a UUID when storing subscription.
        static::creating(function ($subscription) {
            $subscription->uuid = (string) Str::uuid();
        });
    }

    /**
     * Get all of the owning subscribable models.
     *
     * @return
     */
    public function subscribable()
    {
        return $this->morphTo();
    }
}
