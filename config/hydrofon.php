<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Approval Requirement
    |--------------------------------------------------------------------------
    |
    | This value determines if a booking needs to be approved before it can be
    | checked out. By default, no approval is needed and all bookings are
    | approved automatically. Can also be limited to resource type.
    |
    | Supported: "none", "all", "equipment", "facilities"
    |
    */

    'require_approval' => env('HYDROFON_REQUIRE_APPROVAL', 'none'),

    /*
    |--------------------------------------------------------------------------
    | Desk Inclusion Tme
    |--------------------------------------------------------------------------
    |
    | A number of hours to extend the "current" scope with within desk view.
    | By default, bookings that have started and not yet ended are shown.
    | Adding 1 will also include bookings that started one hour ago.
    |
    */

    'desk_inclusion_hours' => [
        'earlier' => 0,
        'later' => 0,
    ],

    /*
    |--------------------------------------------------------------------------
    | Model Pruning
    |--------------------------------------------------------------------------
    |
    | These values determines when older models are automatically removed from
    | the database. Bookings are kept for six months after they've ended,
    | if not checked out. Users get deleted a year after last login.
    |
    */

    'prune_models_after_days' => [
        'bookings' => 180,
        'users' => 365,
    ],

];
