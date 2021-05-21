<?php

use Illuminate\Support\Str;

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

Route::get('/', function(){
	dd( ( $_COOKIE['shopping_cart'] ) );
});
Route::get('/home', 'ProductController@getAllProducts');
Route::get('/product/{id}', 'ProductController@getselectedProduct');
Route::post('/addToCart', 'ProductController@addToCart');
Route::get('/cart', 'ProductController@showCart');
