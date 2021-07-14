<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use App\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(5),
        'summary' =>  $faker->sentence(15),
        'is_published' =>  $faker->numberBetween($min = 0, $max = 1),
        'content' =>  $faker->sentence(100),
        'views' =>  $faker->numberBetween($min = 5000, $max = 2000),
        'likes' =>  $faker->numberBetween($min = 100, $max = 1000),
        'dislikes' =>  $faker->numberBetween($min = 100, $max = 1000),
        // 'category_id' =>  factory(Category::class),
        'user_id' => $faker->numberBetween($min = 27, $max = 28),
    ];
});

