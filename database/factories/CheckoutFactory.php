<?php

use Faker\Generator as Faker;

$factory->define(App\Checkout::class, function (Faker $faker) {
    return [
        'booking_id' => factory(App\Booking::class)->create()->id,
        'user_id'    => factory(App\User::class)->create()->id,
    ];
});
