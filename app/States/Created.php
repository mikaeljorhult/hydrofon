<?php

namespace App\States;

class Created extends BookingState
{
    public static $name = 'created';

    public function label(): string
    {
        return 'Created';
    }
}
