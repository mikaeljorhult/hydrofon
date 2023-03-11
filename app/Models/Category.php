<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Scopes\GroupPolicyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope(new GroupPolicyScope());
    }

    /**
     * Parent category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }

    /**
     * Categories assigned to model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')
                    ->orderBy('name');
    }

    /**
     * Groups the category belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Group::class);
    }

    /**
     * Resources assigned to model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function resources(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Resource::class)
                    ->orderBy('name');
    }
}
