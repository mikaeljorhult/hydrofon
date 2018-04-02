<?php

namespace Hydrofon;

use Illuminate\Database\Eloquent\Model;

class Bucket extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Resources in the bucket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function resources()
    {
        return $this->belongsToMany(\Hydrofon\Resource::class);
    }

    /**
     * Scope to order query by a specific field.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $field
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByField($query, $field)
    {
        // Check which field to order by.
        if (in_array($field, ['name'])) {
            return $query->orderBy($field);
        }

        return $query;
    }

    /**
     * Scope to allow filtering models based on request.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByRequest($query)
    {
        if (request()->has('filter')) {
            $query->where('name', 'LIKE', '%'.request()->get('filter').'%');
        }

        return $query;
    }
}
