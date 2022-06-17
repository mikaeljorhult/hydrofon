<?php

namespace App\States;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class BookingState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
                     ->default(Created::class)
                     ->allowTransition(Created::class, Pending::class)
                     ->allowTransition(Created::class, Completed::class)
                     ->allowTransition(Created::class, AutoApproved::class)
                     ->allowTransition(Pending::class, Approved::class)
                     ->allowTransition(Pending::class, Rejected::class)
                     ->allowTransition(Rejected::class, Pending::class)
                     ->allowTransition(Approved::class, Pending::class)
                     ->allowTransition(Approved::class, CheckedOut::class)
                     ->allowTransition(AutoApproved::class, CheckedOut::class)
                     ->allowTransition(CheckedOut::class, CheckedIn::class, CheckedOutToCheckedIn::class)
                     ->allowTransition(CheckedIn::class, Completed::class);
    }

    public function label(): string
    {
        return 'State';
    }

    public function css(): string
    {
        return 'bg-gray-100 text-gray-800';
    }
}
