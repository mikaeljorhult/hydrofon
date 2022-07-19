<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutStoreRequest;
use App\Models\Booking;
use App\States\CheckedOut;

class CheckoutController extends Controller
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
     * @param  \App\Http\Requests\CheckoutStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CheckoutStoreRequest $request)
    {
        $booking = Booking::findOrFail($request->get('booking_id'));
        $booking->state->transitionTo(CheckedOut::class);

        flash('Booking was checked out.');

        return redirect()->back();
    }
}
