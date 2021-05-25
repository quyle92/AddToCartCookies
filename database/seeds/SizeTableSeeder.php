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
            ['size' => 'XS'],
            ['size' => 'S'],
            ['size' => 'M'],
            ['size' => 'L'],
            ['size' => 'XL'],
            ['size' => 'XXL']
        ];

        DB::table('sizes')->insert($sizes);
    }
}
