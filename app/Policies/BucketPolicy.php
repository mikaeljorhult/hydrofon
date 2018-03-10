<?php

namespace Hydrofon\Policies;

use Hydrofon\Bucket;
use Hydrofon\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BucketPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the bucket.
     *
     * @param \Hydrofon\User   $user
     * @param \Hydrofon\Bucket $bucket
     *
     * @return mixed
     */
    public function view(User $user, Bucket $bucket)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create buckets.
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
     * Determine whether the user can update the bucket.
     *
     * @param \Hydrofon\User   $user
     * @param \Hydrofon\Bucket $bucket
     *
     * @return mixed
     */
    public function update(User $user, Bucket $bucket)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the bucket.
     *
     * @param \Hydrofon\User   $user
     * @param \Hydrofon\Bucket $bucket
     *
     * @return mixed
     */
    public function delete(User $user, Bucket $bucket)
    {
        return $user->isAdmin();
    }
}
