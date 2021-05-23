<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;
use bheller\ImagesGenerator\ImagesGeneratorProvider;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(5),
        'picture'  => 'https://via.placeholder.com/350x150',
        'series_id' => function () {
            // Get random genre id
            return App\Series::inRandomOrder()->first()->id;
        },
    ];
});
