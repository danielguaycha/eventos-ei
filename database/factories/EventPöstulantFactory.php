<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\EventPostulant;
use Faker\Generator as Faker;

$factory->define(EventPostulant::class, function (Faker $faker) {
    return [
        'event_id' => $faker->numberBetween(1, 20),
        'user_id' => $faker->numberBetween(3, 50),
        'status' => 0
    ];
});
