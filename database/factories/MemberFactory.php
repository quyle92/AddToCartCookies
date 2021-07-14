<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Member;
use Faker\Generator as Faker;

$factory->define(Member::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'user_name' => $faker->unique()->userName,
        'email' => $faker->unique()->freeEmail,
        'dob' => new \DateTime(  $faker->numberBetween(1980,2000) . '-01-01' ),
        'address' => $faker->streetAddress ,
        'city' => $faker->city,
        'last_log_in' =>  $faker->dateTimeThisDecade($max = 'now', $timezone = null) ,
    ];
});
