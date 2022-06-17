<?php

namespace App\States;

use App\Models\Booking;
use Spatie\ModelStates\Transition;

class CheckedOutToCheckedIn extends Transition
{
    private Booking $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function handle(): Booking
    {
        $this->booking->state = new CheckedIn($this->booking);

        // Shorten booking if it has not ended yet.
        if ($this->booking->end_time->isFuture() && $this->booking->start_time->isPast()) {
            $this->booking->end_time = now();
        }

        $this->booking->save();

        return $this->booking;
    }
}
