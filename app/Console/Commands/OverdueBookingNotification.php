<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\BookingOverdue;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;

class OverdueBookingNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hydrofon:overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push overdue notifications to users';

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
     */
    public function handle(): int
    {
        $users = User::whereHas('bookings', function (Builder $query) {
            $query->overdue()
                ->where('end_time', '>', $this->dateOfLastNotification(BookingOverdue::class));
        })->get();

        if ($users->isNotEmpty()) {
            Notification::send($users, new BookingOverdue());
        }

        return 0;
    }

    /**
     * Retrieve date of last notification of type.
     *
     * @param  string  $className  FQN of notification type
     */
    private function dateOfLastNotification(string $className): string
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
}
