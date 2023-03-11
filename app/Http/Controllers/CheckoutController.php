<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
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
    public function store(CheckoutStoreRequest $request): RedirectResponse
    {
        $booking = Booking::findOrFail($request->get('booking_id'));
        $booking->state->transitionTo(CheckedOut::class);

        laraflash()
            ->message()
            ->title('Booking was checked out')
            ->content('Booking was checked out successfully.')
            ->success();

        return redirect()->back();
    }
}
