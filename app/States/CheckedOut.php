<?php

namespace App\States;

class CheckedOut extends BookingState
{
    public static $name = 'checkedout';

    public function label(): string
    {
        return 'Checked out';
    }

    public function css(): string
    {
        return 'bg-indigo-100 text-indigo-800';
    }
}
