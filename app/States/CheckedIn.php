<?php

namespace App\States;

class CheckedIn extends BookingState
{
    public static $name = 'checkedin';

    public function label(): string
    {
        return 'Checked in';
    }
}
