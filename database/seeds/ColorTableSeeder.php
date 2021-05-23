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
            ['color' => 'black', 'picture'  => 'https://via.placeholder.com/350x150'],
            ['color' => 'white', 'picture'  => 'https://via.placeholder.com/350x150'],
            ['color' => 'gray', 'picture'  => 'https://via.placeholder.com/350x150'],
            ['color' => 'maroon', 'picture'  => 'https://via.placeholder.com/350x150'],
            ['color' => 'purple', 'picture'  => 'https://via.placeholder.com/350x150'],
            ['color' => 'navy', 'picture'  => 'https://via.placeholder.com/350x150'],
            ['color' => 'teal', 'picture'  => 'https://via.placeholder.com/350x150']
        ];

        DB::table('colors')->insert($colors);
    }
}
