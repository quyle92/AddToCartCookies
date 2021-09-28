<?php
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Facade;


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
Route::get('test', function () {
    // $guest = App\Guest::first();
    // $guest->chat->is_read = 1;
    // $guest->chat->save(); 
    // dd(App\Chat::first()->is_read);
    
    return response()->view('test');
    return view('test');

});

Route::post('authentication', function () {
    Auth::logout();
    $user =  App\Guest::find(27) ;
    Auth::guard('guest')->login($user);
    Session::put('guest',$user);

    return response()->json([
        'msg' => 'success',
        'auth' => Auth::guard('guest')->check(),
        'user' =>  Auth::guard('guest')->user()
    ]);
})->name('authentication');

Route::get('checkAuthentication', function () {
    return response()->json([
        'auth' => Auth::guard('guest')->check()
    ]);
    
});


Auth::routes();
Auth::routes(['verify' => true]);

Route::get('/register', 'Auth\RegisterController@showRegistrationForm');

Route::post('/register', 'Auth\RegisterController@register')->name('register');


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

Route::post('/adminSentMessage', [
    'uses' =>'ChatController@adminSentMessage'
]);
Route::post('/guestSentMessage', [
    'uses' =>'ChatController@guestSentMessage',
     //'middleware' => 'auth'
]);

Route::post('/joinChat', [
    'uses' =>'ChatController@joinChat'
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

