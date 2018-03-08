<?php

use Faker\Generator as Faker;

$factory->define(Hydrofon\Bucket::class, function (Faker $faker) {
    return [
        'name' => $faker->country,
    ];
});
