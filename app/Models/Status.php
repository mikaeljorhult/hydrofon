<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Status extends \Spatie\ModelStatus\Status
{
    use HasFactory;

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
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
     */
    public function created_by(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
