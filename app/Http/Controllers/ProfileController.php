<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): View
    {
        $user = auth()->user();
        $latest = $user->bookings()->with(['resource'])->latest()->limit(10)->get();
        $upcoming = $this->upcomingBookings($user);
        $overdue = $user->bookings()->with(['resource'])->overdue()->get();

        return view('profile.index')
            ->with([
                'user' => $user,
                'latest' => $latest,
                'upcoming' => $upcoming,
                'overdue' => $overdue,
            ]);
    }

    /**
     * Retrieve upcoming bookings for the next week from the database.
     */
    private function upcomingBookings(Authenticatable $user): Collection
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
