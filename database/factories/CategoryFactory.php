<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use App\Post;
use App\Video;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'title' => $faker->unique()->randomElement($array = array ('Business','Health','Entertainment', 'Fashion', 'Travel', 'Sport' , 'Politics')),

    ];
});

$factory->afterCreating(App\Category::class, function ($category, $faker) {
    $category->posts()->saveMany(factory(App\Post::class, 100)->make());
    $category->videos()->saveMany(factory(App\Video::class, 100)->make());
});