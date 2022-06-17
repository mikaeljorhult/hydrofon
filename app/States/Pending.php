<?php

namespace App\States;

class Pending extends BookingState
{
    public static $name = 'pending';

    public function label(): string
    {
        return 'Pending';
    }

    public function css(): string
    {
        return 'bg-yellow-100 text-yellow-800';
    }
}
