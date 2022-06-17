<?php

namespace App\States;

class Completed extends BookingState
{
    public static $name = 'completed';

    public function label(): string
    {
        return 'Completed';
    }
}
