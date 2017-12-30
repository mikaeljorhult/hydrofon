<?php

namespace Hydrofon\Providers;

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
        view()->composer('partials.object-list', 'Hydrofon\Http\ViewComposers\ObjectListComposer');
        view()->composer([
            'partials.object-list.category',
            'partials.object-list.object',
        ], 'Hydrofon\Http\ViewComposers\ObjectListSelectedComposer');
    }
}
