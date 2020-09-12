<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckinDestroyRequest;
use App\Http\Requests\CheckinStoreRequest;
use App\Models\Booking;
use App\Models\Checkin;

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
     * @param \App\Http\Requests\CheckinStoreRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CheckinStoreRequest $request)
    {
        $booking = Booking::findOrFail($request->get('booking_id'));
        $booking->checkin()->create();

        // Shorten booking if it has not ended yet.
        if ($booking->end_time->isFuture() && $booking->start_time->isPast()) {
            $booking->update([
                'end_time' => now(),
            ]);
        }

        flash('Booking was checked in.');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Checkin                             $checkin
     * @param \App\Http\Requests\CheckinDestroyRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Checkin $checkin, CheckinDestroyRequest $request)
    {
        $checkin->delete();

        flash('Checkin was deleted.');

        return redirect()->back();
    }
}
