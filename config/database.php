<?php

return [

    'connections' => [
        'dusk' => [
            'driver' => 'sqlite',
            'url' => env('DB_URL'),
            'database' => database_path('dusk.sqlite'),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],
    ],

    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => false, // disable to preserve original behavior for existing applications
    ],

];
