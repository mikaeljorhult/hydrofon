<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'booking_id',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($checkout) {
            $checkout->user_id = auth()->id();
        });
    }

    /**
     * Booking that was checked out.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function booking()
    {
        return $this->belongsTo(\App\Booking::class);
    }
}
