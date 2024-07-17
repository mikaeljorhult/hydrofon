<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class General extends Settings
{
    public string $require_approval;

    public static function group(): string
    {
        return 'general';
    }
}
