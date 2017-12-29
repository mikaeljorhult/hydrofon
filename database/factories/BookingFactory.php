<?php

use Faker\Generator as Faker;

$factory->define(Hydrofon\Booking::class, function (Faker $faker) {
    return [
        'user_id'       => $user_id = factory(Hydrofon\User::class)->create()->id,
        'object_id'     => factory(Hydrofon\Object::class)->create()->id,
        'created_by_id' => $user_id,
        'start_time'    => now()->addHours(1),
        'end_time'      => now()->addHours(3),
    ];
});

$factory->state(Hydrofon\Booking::class, 'past', [
    'start_time' => now()->subYear(),
    'end_time'   => now()->subYear()->addHour(),
]);
