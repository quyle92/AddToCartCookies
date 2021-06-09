<?php

use Illuminate\Database\Seeder;

class DeliveryMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $delivery_methods = [
            ['delivery_type' => 'self-shipping'],
            ['delivery_type' => 'GHN'],
        ];

        DB::table('delivery_methods')->insert($delivery_methods);
    }
}
