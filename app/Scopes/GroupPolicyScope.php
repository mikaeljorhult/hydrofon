<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class GroupPolicyScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Only limit query if user is not an administrator.
        if (auth()->check() && ! auth()->user()->isAdmin()) {
            // Get IDs of users groups.
            $userGroups = auth()->user()->groups->pluck('id');

            // Models in same group or without any groups are allowed.
            $builder->doesntHave('groups')
                ->orWhereHas('groups', function ($query) use ($userGroups) {
                    $query->whereIn('id', $userGroups);
                });
        }
    }
}
