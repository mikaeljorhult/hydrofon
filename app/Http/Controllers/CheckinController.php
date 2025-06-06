<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckinStoreRequest;
use App\Models\Booking;
use App\States\CheckedIn;
use Illuminate\Http\RedirectResponse;

class CheckinController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(CheckinStoreRequest $request): RedirectResponse
    {
        $booking = Booking::findOrFail($request->get('booking_id'));
        $booking->state->transitionTo(CheckedIn::class);

        flash(json_encode([
            'title' => 'Booking was checked in',
            'message' => 'Booking was checked in successfully.',
        ]), 'success');

        return redirect()->back();
    }
}
