<?php

use Illuminate\Database\Seeder;

class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payment_methods = [
            ['payment_type' => 'cod'],
            ['payment_type' => 'bank'],
            ['payment_type' => 'paypal'],
        ];

        DB::table('payment_methods')->insert($payment_methods);
    }
}
