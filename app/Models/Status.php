<?php

namespace App\Models;

class Status extends \Spatie\ModelStatus\Status
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($status) {
            $status->created_by_id = session()->has('impersonate')
                ? session()->get('impersonated_by')
                : auth()->id();
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
