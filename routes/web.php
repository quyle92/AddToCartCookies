<?php
// use Illuminate\Support\Facades\Session;
// Session::flash('foo','bar');
// dd( Session::get('foo') );

// $mydate = getdate(date("U"));
// $now = DateTime::createFromFormat('U.u', microtime(true));
// $now = $now->format("m-d-Y H:i:s.u");

// $transaction_id = 'COD_' . $mydate['year'] .  ( $mydate['mon'] < 10 ? '0'.$mydate['mon'] : $mydate['mon'] ) . $mydate['mday'] . ( $mydate['hours'] < 10 ? '0'.$mydate['hours'] : $mydate['hours'] ) . ( $mydate['minutes'] < 10 ? '0'.$mydate['minutes'] : $mydate['minutes'] ) . ( $mydate['seconds'] < 10 ? '0'.$mydate['seconds'] : $mydate['seconds'] ) . '.' . Str::substr($now, 20, 7);
// dd($transaction_id) ;

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


Auth::routes();
Auth::routes(['verify' => true]);

Route::get('/register', 'Auth\RegisterController@showRegistrationForm');

Route::post('/register', 'Auth\RegisterController@register')->name('register');

Route::get('/broadcast', 'Admin\AdminController@test');

/*
**E-commerce**
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
Route::post('/checkProducts', [
    'as' => 'app.checkProducts',
    'uses' =>'ProductController@checkProducts'
]);
Route::get('/saveShippingFee', [
    'as' => 'app.saveShippingFee',
    'uses' =>'ProductController@saveShippingFee'
]);

Route::post('/sendMessage', [
    'uses' =>'BroadcastController@sendMessage'
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

/*
COD Payment
 */
Route::post('/checkout/payment/cod', [
    'as' => 'app.codCheckOut',
    'uses' =>'CODController@codCheckOut'
]);

/*
**Admin**
 */
// Route::get('/dashboard','Admin\AdminController@index');

// Route::group( ['prefix' => 'admin', 'namespace' => 'Admin' ], function () {
//    Route::resources([
//     'users' => UserController::class,

//     ]);
// });


Route::get('/admin/{slug}','Admin\AdminController@index');

