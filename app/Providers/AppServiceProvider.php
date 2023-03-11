<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Custom Blade if statement for administrators.
        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->isAdmin();
        });
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
}
