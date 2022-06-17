<?php

namespace App\States;

class AutoApproved extends BookingState
{
    public static $name = 'autoapproved';

    public function label(): string
    {
        return 'Approved';
    }

    public function css(): string
    {
        return 'bg-green-100 text-green-800';
    }
}
