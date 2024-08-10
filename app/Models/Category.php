<?php

namespace App\Models;

use App\Scopes\GroupPolicyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Category extends Model
{
    use HasFactory, HasRecursiveRelationships;

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
     * The "booting" method of the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope(new GroupPolicyScope);
    }

    /**
     * Parent category.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }

    /**
     * Categories assigned to model.
     */
    public function categories(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')
            ->orderBy('name');
    }

    /**
     * Groups the category belongs to.
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Group::class);
    }

    /**
     * Resources assigned to model.
     */
    public function resources(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Resource::class)
            ->orderBy('name');
    }
}
