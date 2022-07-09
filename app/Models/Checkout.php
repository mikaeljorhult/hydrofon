<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    use HasFactory;

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
        return $this->belongsTo(\App\Models\Booking::class);
    }

    /**
     * User that checked out the booking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function created_by()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
