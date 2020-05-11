<?php

use Faker\Generator as Faker;

$factory->define(App\Subscription::class, function (Faker $faker) {
    $subscribables = [
        App\Resource::class,
        App\User::class,
    ];

    $subscribableType = $faker->randomElement($subscribables);
    $subscribable = factory($subscribableType)->create();

    return [
        'subscribable_id'   => $subscribable->id,
        'subscribable_type' => $subscribableType,
    ];
});
