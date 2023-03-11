<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingApproved extends Notification
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
     * @return array
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
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            'icon' => 'check-circle',
            'title' => 'Bookings have been approved!',
            'body' => 'One or more bookings have been approved. You may review your approved bookings in your profile.',
            'url' => route('profile.bookings', ['filter[status]=approved']),
        ];
    }
}
