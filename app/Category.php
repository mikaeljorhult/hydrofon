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
     * Objects assigned to model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function objects()
    {
        return $this->belongsToMany(\Hydrofon\Object::class)
                    ->orderBy('name');
    }
}
