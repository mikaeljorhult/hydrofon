<?php

namespace Hydrofon\Http\Controllers;

use Illuminate\Contracts\Auth\Authenticatable;

class ProfileController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $latest = $user->bookings()->latest()->limit(10)->get();
        $upcoming = $this->upcomingBookings($user);
        $overdue = $user->bookings()->overdue()->get();

        return view('profile')
            ->with([
                'user'     => $user,
                'latest'   => $latest,
                'upcoming' => $upcoming,
                'overdue'  => $overdue,
            ]);
    }

    /**
     * Retrieve upcoming bookings for the next week from the database.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable|null $user
     *
     * @return \Illuminate\Support\Collection
     */
    private function upcomingBookings(Authenticatable $user)
    {
        $upcoming = $user->bookings()
                         ->whereHas('resource', function ($query) {
                             $query->where('is_facility', '=', 0);
                         })
                         ->whereBetween('start_time', [today(), today()->addWeek()])
                         ->get();

        return $upcoming->groupBy(function ($item, $key) {
            return $item->start_time->format('Ymd');
        });
    }
}
