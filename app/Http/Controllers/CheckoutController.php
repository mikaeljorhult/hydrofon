<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutStoreRequest;
use App\Models\Booking;
use App\States\CheckedOut;
use Illuminate\Http\RedirectResponse;

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
