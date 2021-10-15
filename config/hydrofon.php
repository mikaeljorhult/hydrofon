<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Approval Requirement
    |--------------------------------------------------------------------------
    |
    | This value determines if a booking needs to be approved before it can be
    | checked out. By default no approval is needed and all bookings are
    | approved automatically. Can also be limited to resource type.
    |
    | Supported: "none", "all", "equipment", "facilities"
    |
    */

    'require_approval' => env('HYDROFON_REQUIRE_APPROVAL', 'none'),

];
