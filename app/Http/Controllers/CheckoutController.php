<?php

namespace Hydrofon\Http\Controllers;

use Hydrofon\Booking;
use Hydrofon\Checkout;
use Hydrofon\Http\Requests\CheckoutDestroyRequest;
use Hydrofon\Http\Requests\CheckoutStoreRequest;

class CheckoutController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Hydrofon\Http\Requests\CheckoutStoreRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CheckoutStoreRequest $request)
    {
        $booking = Booking::findOrFail($request->get('booking_id'));
        $booking->checkout()->create([
            'user_id' => $request->user()->id,
        ]);

        flash('Booking was checked in.');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Hydrofon\Checkout                             $checkout
     * @param \Hydrofon\Http\Requests\CheckoutDestroyRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Checkout $checkout, CheckoutDestroyRequest $request)
    {
        $checkout->delete();

        flash('Checkout was deleted.');

        return redirect()->back();
    }
}
