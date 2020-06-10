<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class ProfileBookingsController extends Controller
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
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $user     = auth()->user();
        $bookings = QueryBuilder::for(auth()->user()->bookings()->getQuery())
                                ->select('bookings.*')
                                ->with(['checkin', 'checkout', 'user'])
                                ->join('resources', 'resources.id', '=', 'bookings.resource_id')
                                ->allowedFilters(['resource_id', 'start_time', 'end_time'])
                                ->defaultSort('start_time')
                                ->allowedSorts(['resources.name', 'start_time', 'end_time'])
                                ->paginate(15);

        return view('profile.bookings')->with([
            'user'     => $user,
            'bookings' => $bookings,
        ]);
    }
}
