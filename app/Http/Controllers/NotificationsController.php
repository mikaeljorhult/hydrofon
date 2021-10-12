<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
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
