<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Video;
use Faker\Generator as Faker;

$factory->define(Video::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence($nbWords = 5, $variableNbWords = true) ,
        'description' => $faker->sentence($nbWords = 12, $variableNbWords = true) ,
        'uri' => $faker->word . '-' . $faker->word . '-' . $faker->word ,
        'size' => $faker->numberBetween($min = 10, $max = 500) ,
        'views' => $faker->numberBetween($min = 10, $max = 5000) ,
        'likes' => $faker->numberBetween($min = 10, $max = 5000)  ,
        'dislikes' => $faker->numberBetween($min = 10, $max = 50)  ,
        // 'category_id' =>  factory(Category::class)
    ];
});
