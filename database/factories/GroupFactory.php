<?php

use Faker\Generator as Faker;

$factory->define(Hydrofon\Group::class, function (Faker $faker) {
    return [
        'name' => $faker->colorName,
    ];
});
