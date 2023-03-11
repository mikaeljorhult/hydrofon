<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\Models\Booking::class => \App\Policies\BookingPolicy::class,
        \App\Models\Bucket::class => \App\Policies\BucketPolicy::class,
        \App\Models\Category::class => \App\Policies\CategoryPolicy::class,
        \App\Models\Checkin::class => \App\Policies\CheckinPolicy::class,
        \App\Models\Checkout::class => \App\Policies\CheckoutPolicy::class,
        \App\Models\Group::class => \App\Policies\GroupPolicy::class,
        \App\Models\Identifier::class => \App\Policies\IdentifierPolicy::class,
        \App\Models\Resource::class => \App\Policies\ResourcePolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
