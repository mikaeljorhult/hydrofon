<?php

use Faker\Generator as Faker;

$factory->define(Hydrofon\Category::class, function (Faker $faker) {
    return [
        'name' => $faker->country
    ];
});

$factory->state(Hydrofon\Category::class, 'child', [
    'parent_id' => function () {
        return factory(Hydrofon\Category::class)->create()->id;
    }
]);
