<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class SizeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $sizes = [
            ['size' => 'XS', 'price' => $faker->numberBetween($min = 10, $max = 100)],
            ['size' => 'S', 'price' => $faker->numberBetween($min = 10, $max = 100)],
            ['size' => 'M', 'price' => $faker->numberBetween($min = 10, $max = 100)],
            ['size' => 'L', 'price' => $faker->numberBetween($min = 10, $max = 100)],
            ['size' => 'XL', 'price' => $faker->numberBetween($min = 10, $max = 100)],
            ['size' => 'XXL', 'price' => $faker->numberBetween($min = 10, $max = 100)]
        ];

        DB::table('sizes')->insert($sizes);
    }
}
