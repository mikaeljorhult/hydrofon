<?php

namespace App\States;

class Approved extends BookingState
{
    public static $name = 'approved';

    public function label(): string
    {
        return 'Approved';
    }

    public function css(): string
    {
        return 'bg-green-100 text-green-800';
    }
}
