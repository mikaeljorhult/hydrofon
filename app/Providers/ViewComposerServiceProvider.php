<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        view()->composer([
            'partials.resource-tree.base',
        ], \App\Http\ViewComposers\ResourceListComposer::class);
        view()->composer([
            'partials.resource-tree',
            'partials.resource-tree.category',
            'partials.resource-tree.resource',
        ], \App\Http\ViewComposers\ResourceListSelectedComposer::class);
    }
}
