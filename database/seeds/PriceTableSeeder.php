<?php

use Illuminate\Database\Seeder;

class PriceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('prices')->insert([
            ['product_id' => '1', 'size' => 'S', 'price' => 10],
            ['product_id' => '1', 'size' => 'M', 'price' => 20],
			['product_id' => '1', 'size' => 'L', 'price' => 30],
			['product_id' => '2', 'size' => 'S', 'price' => 70],
            ['product_id' => '2', 'size' => 'M', 'price' => 80],
			['product_id' => '2', 'size' => 'L', 'price' => 90],
			['product_id' => '3', 'size' => 'S', 'price' => 180],
            ['product_id' => '3', 'size' => 'M', 'price' => 190],
			['product_id' => '3', 'size' => 'L', 'price' => 200],
        ]);
    }
}
