<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
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
        static::creating(function ($approval) {
            $approval->user_id = auth()->id();
        });
    }

    /**
     * Approval belongs to a booking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function booking()
    {
        return $this->belongsTo(\App\Models\Booking::class);
    }

    /**
     * Approval is created by a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
