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
Route::get('/product/{id}', 'ProductController@getSelectedStyle');
Route::post('/addToCart', 'ProductController@addToCart');
Route::get('/cart', 'ProductController@showCart');
Route::get('/getSelectedStyleSet', 'ProductController@getSelectedStyleSet');
Route::get('/getVariationSet', 'ProductController@getVariationSet');
Route::get('/getMaxQuantityForEachItem', 'ProductController@getMaxQuantityForEachItem');
Route::get('/checkout', [
    'as' => 'app.checkout',
    'uses' =>'ProductController@checkout'
]);

/*
Paypal Omnipay
 */
Route::post('/checkout/payment/paypal', [
    'name' => 'PayPal Express Checkout',
    'as' => 'checkout.payment.paypal',
    'uses' => 'PayPalController@checkout',
]);

Route::get('/paypal/checkout/completed', [
    'name' => 'PayPal Express Checkout',
    'as' => 'paypal.checkout.completed',
    'uses' => 'PayPalController@completed',
]);

Route::get('/paypal/checkout/cancelled', [
    'name' => 'PayPal Express Checkout',
    'as' => 'paypal.checkout.cancelled',
    'uses' => 'PayPalController@cancelled',
]);

Route::get('/thankyou', [
    'as' => 'app.thankyou',
    'uses' =>'PayPalController@thankyou'
]);

