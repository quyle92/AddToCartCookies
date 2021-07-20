<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Comment;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'commentable_type' => 'App\\' . $faker->randomElements(['Post', 'Video'])[0] ,
        'commentable_id' => $faker->numberBetween($min = 1, $max = 100),
        'comment_content' => $faker->sentence(10),
        'member_id' => $faker->numberBetween($min = 1, $max = 50), 
        'created_at' => new \DateTime( date("Y-m-d H:i:s", $faker->numberBetween($min = 1615714594, $max = 1626255394))), 
        'updated_at' => new \DateTime( date("Y-m-d H:i:s", $faker->numberBetween($min = 1615714594, $max = 1626255394))), 
    ];
});
