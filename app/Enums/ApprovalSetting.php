<?php

namespace App\Enums;

enum ApprovalSetting: string
{
    case NONE = 'none';
    case ALL = 'all';
    case EQUIPMENT = 'equipment';
    case FACILITIES = 'facilities';

    public function label(): string
    {
        return match ($this)
        {
            self::NONE => 'No approval',
            self::ALL => 'Approval for everything',
            self::EQUIPMENT => 'Only equipment',
            self::FACILITIES => 'Only facilities',
        };
    }

    public static function options(): array
    {
        $cases = static::cases();

        return array_combine(
            array_column($cases, 'value'),
            array_map(
                fn(\App\Enums\ApprovalSetting $setting) => $setting->label(),
                $cases
            ),
        );
    }
}
