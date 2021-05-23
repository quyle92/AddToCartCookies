<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Size;
use Faker\Generator as Faker;

$factory->define(Size::class, function (Faker $faker) {
    return [
        'sizeName' => $faker->unique()->randomElement($array = array ('S','M','L')) 
    ];
});
