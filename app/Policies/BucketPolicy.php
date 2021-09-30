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
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bucket  $bucket
     * @return mixed
     */
    public function view(User $user, Bucket $bucket)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create buckets.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the bucket.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bucket  $bucket
     * @return mixed
     */
    public function update(User $user, Bucket $bucket)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the bucket.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bucket  $bucket
     * @return mixed
     */
    public function delete(User $user, Bucket $bucket)
    {
        return $user->isAdmin();
    }
}
