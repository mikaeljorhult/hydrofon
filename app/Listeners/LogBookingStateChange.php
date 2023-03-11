<?php

namespace App\Listeners;

use App\States\Approved;
use App\States\AutoApproved;
use App\States\CheckedIn;
use App\States\CheckedOut;
use App\States\Completed;
use App\States\Created;
use App\States\Pending;
use App\States\Rejected;
use Spatie\ModelStates\Events\StateChanged;

class LogBookingStateChange
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Spatie\ModelStates\Events\StateChanged  $event
     * @return void
     */
    public function handle(StateChanged $event): void
    {
        $eventType = match (get_class($event->finalState)) {
            Created::class => 'created',
            Pending::class => 'pending',
            AutoApproved::class => 'autoapproved',
            Approved::class => 'approved',
            Rejected::class => 'rejected',
            CheckedIn::class => 'checkedin',
            CheckedOut::class => 'checkedout',
            Completed::class => 'completed',
        };

        if ($eventType !== 'created') {
            activity()
                ->performedOn($event->model)
                ->event($event->finalState->label())
                ->log($eventType);
        }
    }
}
