<?php

namespace Hydrofon\Console\Commands;

use Hydrofon\Booking;
use Hydrofon\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class CleanupOldRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hydrofon:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old records from database';

    /**
     *
     *
     * @var array
     */
    private $models = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        // Bookings should be deleted.
        $this->models[Booking::class] = function (Builder $query) {
            // Booking ended more than 6 months ago.
            $query->whereDate('end_time', '<=', now()->subMonths(6));
        };

        // Users should be deleted.
        $this->models[User::class] = function (Builder $query) {
            $query->where(function ($query) {
                // User has never logged in and was created more tha a year ago.
                $query->whereNull('last_logged_in_at')
                      ->whereDate('created_at', '<=', now()->subMonths(12));
            })->orWhere(function ($query) {
                // User has logged in but not been active for more than a year.
                $query->whereNotNull('last_logged_in_at')
                      ->whereDate('last_logged_in_at', '<=', now()->subMonths(12));
            });
        };
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Cleaning up old database records...');

        // Go through each type of model that should be cleaned out.
        foreach ($this->models as $model => $scope) {
            // Delete all models matching criteria.
            $count = $model::where($scope)->delete();

            // Print number of models that was deleted.
            $this->info($count . ' ' . with(new $model)->getTable() . ' deleted.');
        }

        $this->info('Done.');
    }
}
