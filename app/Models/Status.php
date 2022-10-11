<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Status extends \Spatie\ModelStatus\Status
{
    use HasFactory;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($status) {
            if ($status->created_by_id === null) {
                $status->created_by_id = session()->has('impersonate')
                    ? session()->get('impersonated_by')
                    : auth()->id();
            }
        });
    }

    /**
     * User whose action generated the status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function created_by()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
