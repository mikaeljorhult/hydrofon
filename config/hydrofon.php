<?php

return [

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
