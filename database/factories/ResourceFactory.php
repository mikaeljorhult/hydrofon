<?php

use Faker\Generator as Faker;

$factory->define(App\Resource::class, function (Faker $faker) {
    return [
        'name'        => $faker->city,
        'description' => $faker->sentence,
        'is_facility' => false,
    ];
});

$factory->state(App\Resource::class, 'facility', [
    'is_facility' => true,
]);
