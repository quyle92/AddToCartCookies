<?php

use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            [ 'productName' => 'jeans', 'number' => 1, "picture" => 'https://media3.scdn.vn/img2/2018/9_14/qAuye8_simg_de2fe0_500x500_maxb.jpg', 'created_at' => new DateTime( date('Y-m-d H:i:s'), new DateTimeZone('Asia/Ho_Chi_Minh') ), 'fullNumber' => 'JEAN-001', 'series_id' => 1],
            ['productName' => 'Jacket', 'number' => 1, "picture" => 'https://media3.scdn.vn/img2/2018/1_6/zHhuRX_simg_de2fe0_500x500_maxb.jpg', 'created_at' => new DateTime( date('Y-m-d H:i:s'), new DateTimeZone('Asia/Ho_Chi_Minh') ),'fullNumber' => 'JACKET-001', 'series_id' => 2 ],
			['productName' => 'sunglasses', 'number' => 1, "picture" => 'https://media3.scdn.vn/img3/2019/7_31/6Kg1XO_simg_de2fe0_500x500_maxb.jpg','created_at' => new DateTime( date('Y-m-d H:i:s'), new DateTimeZone('Asia/Ho_Chi_Minh') ),'fullNumber' => 'SUNGLASSES-001', 'series_id' => 3 ]
        ]);
    }
}
