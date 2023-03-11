<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckinStoreRequest;
use App\Models\Booking;
use App\States\CheckedIn;
use Illuminate\Http\RedirectResponse;

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
     */
    public function store(CheckinStoreRequest $request): RedirectResponse
    {
        $booking = Booking::findOrFail($request->get('booking_id'));
        $booking->state->transitionTo(CheckedIn::class);

        laraflash()
            ->message()
            ->title('Booking was checked in')
            ->content('Booking was checked in successfully.')
            ->success();

        return redirect()->back();
    }
}
