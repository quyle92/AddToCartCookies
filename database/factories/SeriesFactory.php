<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Series;
use Faker\Generator as Faker;

$factory->define(Series::class, function (Faker $faker) {
    return [
        'prefix' => $faker->unique()->randomElement(['AAA', 'BBB', 'CCC'])
    ];
});
