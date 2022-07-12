<?php

namespace App\Listeners;

use App\Notifications\BookingApproved;
use App\Notifications\BookingRejected;
use App\States\Approved;
use App\States\Rejected;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\ModelStates\Events\StateChanged;

class NotifyUserOfBookingStateChange
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
    public function handle(StateChanged $event)
    {
        $notification = match (get_class($event->finalState)) {
            Approved::class => new BookingApproved(),
            Rejected::class => new BookingRejected(),
            default => null,
        };

        if ($notification) {
            $event->model->user->notify($notification);
        }
    }
}
