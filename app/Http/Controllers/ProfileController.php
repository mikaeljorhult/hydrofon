<?php

namespace App\Http\Controllers;

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
     * Handle the incoming request.
     *
     * @return \Illuminate\View\View
     */
    public function __invoke()
    {
        $user = auth()->user();
        $latest = $user->bookings()->with(['resource'])->latest()->limit(10)->get();
        $upcoming = $this->upcomingBookings($user);
        $overdue = $user->bookings()->with(['resource'])->overdue()->get();

        return view('profile.index')
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
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $user
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
