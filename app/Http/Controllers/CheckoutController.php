<?php

namespace Hydrofon\Http\Controllers;

use Hydrofon\Booking;
use Hydrofon\Checkout;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $booking = Booking::findOrFail($request->get('booking_id'));
        $booking->checkout()->create([
            'user_id' => $request->user()->id,
        ]);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Hydrofon\Checkout $checkout
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Checkout $checkout)
    {
        $checkout->delete();

        return redirect()->back();
    }
}
