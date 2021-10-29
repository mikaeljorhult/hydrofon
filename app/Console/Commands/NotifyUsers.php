<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\BookingApproved;
use App\Notifications\BookingAwaitingApproval;
use App\Notifications\BookingOverdue;
use App\Notifications\BookingRejected;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;

class NotifyUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hydrofon:notifications {--type=all : Type of notification to process}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push notifications to users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $requireApproval = config('hydrofon.require_approval') !== 'none';

        if ($this->option('type') === 'all') {
            $this->overdueBookings();
            $this->awaitingApproval();
            $this->approvedBookings();
            $this->rejectedBookings();
        } elseif ($this->option('type') === 'overdue') {
            $this->overdueBookings();
        } elseif ($this->option('type') === 'approval' && $requireApproval) {
            $this->awaitingApproval();
        } elseif ($this->option('type') === 'approved' && $requireApproval) {
            $this->approvedBookings();
        } elseif ($this->option('type') === 'rejected' && $requireApproval) {
            $this->rejectedBookings();
        }

        return 0;
    }

    /**
     * Retrieve date of last notification of type.
     *
     * @param  string  $className  FQN of notification type
     *
     * @return string
     */
    private function dateOfLastNotification(string $className)
    {
        $notification = \DB::table('notifications')
                           ->select(['created_at', 'read_at'])
                           ->where('type', '=', $className)
                           ->latest()
                           ->first();

        // Don't notify users again if they have an unread notification of same type.
        if ($notification && $notification->read_at === null) {
            return now()->addHour();
        }

        return $notification->created_at ?? '1970-01-01 00:00:00';
    }

    /**
     * Notify users with approved bookings.
     */
    private function approvedBookings()
    {
        $users = User::whereHas('bookings', function (Builder $query) {
            $query->approved()->whereHas('statuses', function (Builder $query) {
                $query->where('created_at', '>', $this->dateOfLastNotification(BookingApproved::class));
            });
        })->get();

        if ($users->isNotEmpty()) {
            Notification::send($users, new BookingApproved());
        }
    }

    /**
     * Notify users with rejected bookings.
     */
    private function rejectedBookings()
    {
        $users = User::whereHas('bookings', function (Builder $query) {
            $query->rejected()->whereHas('statuses', function (Builder $query) {
                $query->where('created_at', '>', $this->dateOfLastNotification(BookingRejected::class));
            });
        })->get();

        if ($users->isNotEmpty()) {
            Notification::send($users, new BookingRejected());
        }
    }

    /**
     * Notify users with overdue bookings.
     */
    private function overdueBookings()
    {
        $users = User::whereHas('bookings', function (Builder $query) {
            $query->overdue()
                  ->where('end_time', '>', $this->dateOfLastNotification(BookingOverdue::class));
        })->get();


        if ($users->isNotEmpty()) {
            Notification::send($users, new BookingOverdue());
        }
    }

    /**
     * Notify approvers of new bookings waiting for approval.
     */
    private function awaitingApproval()
    {
        $approvers = User::whereHas('approvingGroups.users.bookings', function (Builder $query) {
            $query->currentStatus('pending')
                  ->where('updated_at', '>', $this->dateOfLastNotification(BookingAwaitingApproval::class));
        })->get();


        if ($approvers->isNotEmpty()) {
            Notification::send($approvers, new BookingAwaitingApproval());
        }
    }
}
