<?php

namespace Hydrofon\Policies;

use Hydrofon\User;
use Hydrofon\Category;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the category.
     *
     * @param \Hydrofon\User     $user
     * @param \Hydrofon\Category $category
     *
     * @return mixed
     */
    public function view(User $user, Category $category)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create categories.
     *
     * @param \Hydrofon\User $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the category.
     *
     * @param \Hydrofon\User     $user
     * @param \Hydrofon\Category $category
     *
     * @return mixed
     */
    public function update(User $user, Category $category)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the category.
     *
     * @param \Hydrofon\User     $user
     * @param \Hydrofon\Category $category
     *
     * @return mixed
     */
    public function delete(User $user, Category $category)
    {
        return $user->isAdmin();
    }
}
