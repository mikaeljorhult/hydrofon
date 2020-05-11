<?php

use Faker\Generator as Faker;

$factory->define(App\Bucket::class, function (Faker $faker) {
    return [
        'name' => $faker->country,
    ];
});
