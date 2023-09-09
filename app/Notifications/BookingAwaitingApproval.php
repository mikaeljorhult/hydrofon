<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingAwaitingApproval extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     */
    public function via($notifiable): array
    {
        $hasNotifications = $notifiable->unreadNotifications()
            ->where('type', get_class($this))
            ->exists();

        return $hasNotifications ? [] : ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toArray($notifiable): array
    {
        return [
            'icon' => 'clipboard-check',
            'title' => 'Bookings awaiting your approval!',
            'body' => 'One or more bookings are waiting for approval. You may review to approve or reject them.',
            'url' => route('approvals.index'),
        ];
    }
}
