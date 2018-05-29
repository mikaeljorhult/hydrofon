<?php

namespace Hydrofon\Http\Controllers;

use Hydrofon\Booking;
use Hydrofon\Checkin;
use Hydrofon\Http\Requests\CheckinDestroyRequest;
use Hydrofon\Http\Requests\CheckinStoreRequest;

class CheckinController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Hydrofon\Http\Requests\CheckinStoreRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CheckinStoreRequest $request)
    {
        $booking = Booking::findOrFail($request->get('booking_id'));
        $booking->checkin()->create([
            'user_id' => $request->user()->id,
        ]);

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
     * @param \Hydrofon\Checkin                             $checkin
     * @param \Hydrofon\Http\Requests\CheckinDestroyRequest $request
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
