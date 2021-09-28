<?php

namespace App\Http\Livewire;

use Livewire\Component;

class NotificationsIndicator extends Component
{
    public function render()
    {
        return view('livewire.notifications-indicator')
            ->with('hasUnreadNotifications', $this->hasUnreadNotifications());
    }

    private function hasUnreadNotifications()
    {
        return auth()->user()->unreadNotifications()->exists();
    }
}
