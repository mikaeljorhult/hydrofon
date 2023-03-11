<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Users that can approve bookings for users in group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function approvers(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\User::class, 'approver_group');
    }

    /**
     * Categories in the group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Resource::class);
    }

    /**
     * Resources in the group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function resources(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Resource::class);
    }

    /**
     * Users in the group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\User::class);
    }
}
