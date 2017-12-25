<?php

namespace Hydrofon\Http\Controllers;

use Hydrofon\Booking;
use Hydrofon\Checkin;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $booking = Booking::findOrFail($request->get('booking_id'));
        $booking->checkin()->create([
            'user_id' => $request->user()->id,
        ]);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Hydrofon\Checkin $checkin
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Checkin $checkin)
    {
        $checkin->delete();

        return redirect()->back();
    }
}
