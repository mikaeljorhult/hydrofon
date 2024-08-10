<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\BookingApproved;
use App\Notifications\BookingAwaitingApproval;
use App\Notifications\BookingRejected;
use App\States\Approved;
use App\States\Pending;
use App\States\Rejected;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;
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
     */
    public function handle(StateChanged $event): void
    {
        $stateClass = get_class($event->finalState);

        $notification = match (get_class($event->finalState)) {
            Pending::class => new BookingAwaitingApproval,
            Approved::class => new BookingApproved,
            Rejected::class => new BookingRejected,
            default => null,
        };

        if ($notification) {
            if ($stateClass === Pending::class) {
                $users = User::whereHas('approvingGroups', function (Builder $query) use ($event) {
                    $query->whereIn('id', $event->model->user->groups()->pluck('id'));
                })->get();

                Notification::send($users, $notification);
            } else {
                $event->model->user->notify($notification);
            }
        }
    }
}
