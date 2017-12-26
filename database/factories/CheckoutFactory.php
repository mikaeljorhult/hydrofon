<?php

use Faker\Generator as Faker;

$factory->define(Hydrofon\Checkout::class, function (Faker $faker) {
    return [
        'booking_id' => factory(Hydrofon\Booking::class)->create()->id,
        'user_id'    => factory(Hydrofon\User::class)->create()->id,
    ];
});
