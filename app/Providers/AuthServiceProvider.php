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
        \App\Booking::class    => \App\Policies\BookingPolicy::class,
        \App\Bucket::class     => \App\Policies\BucketPolicy::class,
        \App\Category::class   => \App\Policies\CategoryPolicy::class,
        \App\Checkin::class    => \App\Policies\CheckinPolicy::class,
        \App\Checkout::class   => \App\Policies\CheckoutPolicy::class,
        \App\Group::class      => \App\Policies\GroupPolicy::class,
        \App\Identifier::class => \App\Policies\IdentifierPolicy::class,
        \App\Resource::class   => \App\Policies\ResourcePolicy::class,
        \App\User::class       => \App\Policies\UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
