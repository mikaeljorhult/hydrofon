<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class General extends Settings
{
    public string $require_approval;

    public int $desk_inclusion_earlier;

    public int $desk_inclusion_later;

    public int $prune_bookings;

    public int $prune_users;

    public static function group(): string
    {
        return 'general';
    }
}
