<?php

namespace Hydrofon\Providers;

use Illuminate\Database\SQLiteConnection;
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
