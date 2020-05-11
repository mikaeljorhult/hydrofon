<?php

use Faker\Generator as Faker;

$factory->define(App\Booking::class, function (Faker $faker) {
    return [
        'user_id'       => $user_id = factory(App\User::class)->create()->id,
        'resource_id'   => factory(App\Resource::class)->create()->id,
        'created_by_id' => $user_id,
        'start_time'    => now()->addHours(1),
        'end_time'      => now()->addHours(3),
    ];
});

$factory->state(App\Booking::class, 'current', [
    'start_time' => now()->subHour(),
    'end_time'   => now()->addHour(),
]);

$factory->state(App\Booking::class, 'past', [
    'start_time' => now()->subYear(),
    'end_time'   => now()->subYear()->addHour(),
]);

$factory->state(App\Booking::class, 'future', [
    'start_time' => now()->addYear(),
    'end_time'   => now()->addYear()->addHour(),
]);
