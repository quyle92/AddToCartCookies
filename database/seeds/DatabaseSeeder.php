<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call([
         	//SeriesTableSeeder::class,
            //ColorTableSeeder::class,
            //SizeTableSeeder::class,
            //StyleTableSeeder::class,
            //ProductTableSeeder::class,
            //DeliveryMethodsSeeder::class,
            //PaymentMethodsSeeder::class,
            //CategorySeeder::class,
             // MemberSeeder::class,
             CommentSeeder::class,
         ]);
        
    }
}
