<?php

use Illuminate\Database\Seeder;

class SeriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prefixes = [
            ['prefix' => 'UNISEX'],
            ['prefix' => 'WOMEN'],
            ['prefix' => 'MEN'],
        ];

        DB::table('series')->insert($prefixes);
    }

}
