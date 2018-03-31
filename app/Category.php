<?php

namespace Hydrofon;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'name',
    ];

    /**
     * Parent category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(\Hydrofon\Category::class);
    }

    /**
     * Categories assigned to model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->hasMany(\Hydrofon\Category::class, 'parent_id');
    }

    /**
     * Resources assigned to model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function resources()
    {
        return $this->belongsToMany(\Hydrofon\Resource::class)
                    ->orderBy('name');
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
        } elseif (in_array($field, ['parent'])) {
            // Join tables and order by relationship name.
            return $query
                ->leftJoin('categories as parent', 'parent.id', '=', 'categories.parent_id')
                ->select('categories.*', 'parent.name AS parent_name')
                ->orderBy('parent_name')
                ->orderBy('name');
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
