<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Config;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function approvalIsNotRequired()
    {
        Config::set('hydrofon.require_approval', 'none');
    }

    public function approvalIsRequired()
    {
        Config::set('hydrofon.require_approval', 'all');
    }

    public function approvalIsRequiredForFacilities()
    {
        Config::set('hydrofon.require_approval', 'facilities');
    }

    public function approvalIsRequiredForEquipment()
    {
        Config::set('hydrofon.require_approval', 'equipment');
    }
}
