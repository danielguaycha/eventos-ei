<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Event;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {
    $fI = \Carbon\Carbon::now();
    $fF = $fI->addWeek();
    return [
        'title' => $faker->sentence,
        'slug' => $faker->slug,
        'short_link' => \Illuminate\Support\Str::random(8),
        'description' => $faker->sentence(5),
        'hours' => $faker->numberBetween(10, 40),
        'type' => $faker->randomElement([Event::TypeAprovacion, Event::TypeAsistencia, Event::TypeAsistenciaAprovation]),
        'f_inicio' => $fI,
        'f_fin' => $fF,
        'matricula_inicio' => $fI,
        'matricula_fin' => $fF,
        'sponsor_id' => $faker->numberBetween(1,3)
    ];
});
