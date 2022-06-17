<?php

namespace App\States;

class Rejected extends BookingState
{
    public static $name = 'rejected';

    public function label(): string
    {
        return 'Rejected';
    }

    public function css(): string
    {
        return 'bg-red-100 text-red-800';
    }
}
