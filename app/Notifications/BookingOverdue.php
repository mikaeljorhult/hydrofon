<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingOverdue extends Notification
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
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toArray($notifiable): array
    {
        return [
            'icon' => 'exclamation-triangle',
            'title' => 'Bookings overdue!',
            'body' => 'One or more bookings are overdue. Please return the equipment as soon as possible.',
            'url' => route('profile.bookings', ['filter[overdue]=1']),
        ];
    }
}
