<?php

use Faker\Generator as Faker;

$factory->define(Hydrofon\Subscription::class, function (Faker $faker) {
    $subscribables = [
        Hydrofon\Resource::class,
        Hydrofon\User::class,
    ];

    $subscribableType = $faker->randomElement($subscribables);
    $subscribable     = factory($subscribableType)->create();

    return [
        'subscribable_id'   => $subscribable->id,
        'subscribable_type' => $subscribableType,
    ];
});
