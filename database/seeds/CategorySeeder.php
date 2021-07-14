<?php

use App\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {    
      // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
      // DB::table('categories')->truncate();
      // DB::table('posts')->truncate();
      // DB::table('videos')->truncate();
      // DB::statement('SET FOREIGN_KEY_CHECKS=1;');
      
     factory(Category::class, 7)->create();
    }
}
