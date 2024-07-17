<?php

namespace Tests;

use App\Enums\ApprovalSetting;
use App\Settings\General;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function approvalIsNotRequired()
    {
        General::fake([
            'require_approval' => ApprovalSetting::NONE->value,
        ]);
    }

    public function approvalIsRequired()
    {
        General::fake([
            'require_approval' => ApprovalSetting::ALL->value,
        ]);
    }

    public function approvalIsRequiredForEquipment()
    {
        General::fake([
            'require_approval' => ApprovalSetting::EQUIPMENT->value,
        ]);
    }

    public function approvalIsRequiredForFacilities()
    {
        General::fake([
            'require_approval' => ApprovalSetting::FACILITIES->value,
        ]);
    }
}
