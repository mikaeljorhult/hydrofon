<?php

namespace App\States;

class CheckedOut extends BookingState
{
    public static $name = 'checkedout';

    public function label(): string
    {
        return 'Checked out';
    }
}
