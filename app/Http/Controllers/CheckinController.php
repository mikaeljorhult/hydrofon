<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckinDestroyRequest;
use App\Http\Requests\CheckinStoreRequest;
use App\Models\Booking;
use App\Models\Checkin;
use App\States\CheckedIn;

class CheckinController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CheckinStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CheckinStoreRequest $request)
    {
        $booking = Booking::findOrFail($request->get('booking_id'));
        $booking->state->transitionTo(CheckedIn::class);

        flash('Booking was checked in.');

        return redirect()->back();
    }
}
