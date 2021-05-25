<?php

use Illuminate\Database\Seeder;

class ColorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $colors = [
            ['color' => 'black'],
            ['color' => 'white'],
            ['color' => 'gray'],
            ['color' => 'maroon'],
            ['color' => 'purple'],
            ['color' => 'navy'],
            ['color' => 'teal']
        ];

        DB::table('colors')->insert($colors);
    }
}
