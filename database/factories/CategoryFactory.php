<?php

use Faker\Generator as Faker;

$factory->define(Hydrofon\Category::class, function (Faker $faker) {
    return [
        'name' => $faker->country
    ];
});
