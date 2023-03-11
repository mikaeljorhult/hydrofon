<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
     */
    public function approvers(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\User::class, 'approver_group');
    }

    /**
     * Categories in the group.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Resource::class);
    }

    /**
     * Resources in the group.
     */
    public function resources(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Resource::class);
    }

    /**
     * Users in the group.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\User::class);
    }
}
