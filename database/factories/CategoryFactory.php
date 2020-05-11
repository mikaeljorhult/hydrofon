<?php

use Faker\Generator as Faker;

$factory->define(App\Category::class, function (Faker $faker) {
    return [
        'name' => $faker->country,
    ];
});

$factory->state(App\Category::class, 'child', [
    'parent_id' => function () {
        return factory(App\Category::class)->create()->id;
    },
]);
