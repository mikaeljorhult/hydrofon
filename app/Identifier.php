<?php

namespace Hydrofon;

use Illuminate\Database\Eloquent\Model;

class Identifier extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value',
    ];

    /**
     * Get the owning identifiable models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function identifiable()
    {
        return $this->morphTo();
    }
}
