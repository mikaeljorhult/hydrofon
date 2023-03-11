<?php

namespace App\Policies;

use App\Models\Bucket;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BucketPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the bucket.
     */
    public function view(User $user, Bucket $bucket): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create buckets.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the bucket.
     */
    public function update(User $user, Bucket $bucket): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the bucket.
     */
    public function delete(User $user, Bucket $bucket): bool
    {
        return $user->isAdmin();
    }
}
