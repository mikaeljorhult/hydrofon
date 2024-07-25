<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.require_approval', 'none');
        $this->migrator->add('general.desk_inclusion_earlier', 0);
        $this->migrator->add('general.desk_inclusion_later', 0);
    }
};
