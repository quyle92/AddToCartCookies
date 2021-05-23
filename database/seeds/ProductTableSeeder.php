<?php

use App\Product;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {   
        Product::truncate();


        $styles = App\Style::all();
        $styles->each( function($style) use($faker) {
            $sizes = App\Size::all();
            $sizes->each( function($size) use($faker, $style) {
                $colors = App\Color::all();
                $colors->each( function($color) use($faker, $style, $size) {
                    $series = App\Series::all();
                    $series_id =  Str::substr($style->style, 0, 1) === 'U' ? $series[0]->id : (Str::substr($style->style, 0, 1) === 'W' ? $series[1]->id : $series[2]->id );

                    App\Product::create([
                       // 'series_id' => App\Series::inRandomOrder()->first()->id,
                        'series_id' =>  $series_id,
                        'color_id' => $color->id,
                        'size_id' => $size->id,
                        'style_id' => $style->id,
                        'quantity' => $faker->numberBetween($min = 5, $max = 20),
                    ]);
                    
                });
            });

        });

    }

   
}



