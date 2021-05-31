<?php

use App\Size;
 $priceQuantity = DB::table('products')
 					->join('sizes', 'products.size_id', '=', 'sizes.id')
 					->join('colors', 'products.color_id', '=', 'colors.id')
 					->where('style_id', 1)->select('color', 'size', 'quantity','price')->get();



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/','ProductController@getAllProducts');
Route::get('/home', 'ProductController@getAllProducts');
Route::get('/product/{id}', 'ProductController@getselectedProduct');
Route::post('/addToCart', 'ProductController@addToCart');
Route::get('/cart', 'ProductController@showCart');
Route::get('/getPriceQuantity', 'ProductController@getPriceQuantity');
Route::get('/getVariationSet', 'ProductController@getVariationSet');
