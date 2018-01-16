<?php

namespace Hydrofon\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Hydrofon\Booking::class    => \Hydrofon\Policies\BookingPolicy::class,
        \Hydrofon\Category::class   => \Hydrofon\Policies\CategoryPolicy::class,
        \Hydrofon\Checkin::class    => \Hydrofon\Policies\CheckinPolicy::class,
        \Hydrofon\Checkout::class   => \Hydrofon\Policies\CheckoutPolicy::class,
        \Hydrofon\Identifier::class => \Hydrofon\Policies\IdentifierPolicy::class,
        \Hydrofon\Object::class     => \Hydrofon\Policies\ObjectPolicy::class,
        \Hydrofon\User::class       => \Hydrofon\Policies\UserPolicy::class,
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
