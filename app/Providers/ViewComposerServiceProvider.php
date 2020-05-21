<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        view()->composer([
            'partials.resource-tree',
        ], \App\Http\ViewComposers\ResourceListComposer::class);
        view()->composer([
            'partials.resource-tree',
            'partials.resource-tree.category',
            'partials.resource-tree.resource',
        ], \App\Http\ViewComposers\ResourceListSelectedComposer::class);
    }
}
