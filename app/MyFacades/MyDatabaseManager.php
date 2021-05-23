<?php
namespace App\MyFacades; 

use Illuminate\Support\Facades\DB;
use \Illuminate\Database\DatabaseManager;

class MyDatabaseManager extends DatabaseManager {

	public static function getProducts()
    {
       	return DB::table('products')
            ->join('colors', 'colors.id', '=', 'color_id')
            ->join('sizes', 'sizes.id', '=', 'size_id')
            ->join('styles', 'styles.id', '=', 'style_id')
            ->select('products.id','products.fullNumber','styles.style', 'sizes.size', 'colors.color','colors.picture',  'products.quantity','sizes.price');
           
    }

}