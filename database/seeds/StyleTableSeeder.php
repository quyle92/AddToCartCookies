<?php

use Illuminate\Database\Seeder;

class StyleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
 
    public function run()
    {
        $styles = [
            ['style' => 'Unisex Short Sleeve T-Shirt'],
            ['style' => 'Unisex Tank Top'],
            ['style' => 'Unisex Pullover Hoodie'],
            ['style' => 'Women\'s Short Sleeve T-Shirt'],
            ['style' => 'Women\'s Flowy Long Sleeve Shirt'],
            ['style' => 'Men\'s Polo Shirt']
        ];

        DB::table('styles')->insert($styles);
    }

}
