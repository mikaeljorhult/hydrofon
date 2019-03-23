<?php

namespace Hydrofon\Providers;

use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Allow foreign keys with SQLite.
        if (DB::connection() instanceof SQLiteConnection) {
            DB::statement(DB::raw('PRAGMA foreign_keys=1'));
        }

        // Custom Blade if statement for administrators.
        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->isAdmin();
        });

        // Collection macro to walk through and return nested resources.
        Collection::macro('nested', function($parameter) {
            $items = collect($this->items);

            foreach ($items as $item) {
                $items = $items->merge($item->$parameter->nested($parameter));
            }

            return $items;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
