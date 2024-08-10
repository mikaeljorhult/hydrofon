<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        $notifications = auth()->user()->notifications()
            ->orderBy('read_at', 'desc')
            ->latest()
            ->limit(8)
            ->get();

        auth()->user()->unreadNotifications()->update(['read_at' => now()]);

        return view('notifications.index')
            ->with('notifications', $notifications);
    }
}
