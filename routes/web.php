<?php
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

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
Route::get('test', function (){
    return view('test');
});
// Route::post('authentication', 'ChatController@joinChat');
// Route::post('checkAuthentication', 'ChatController@guestSentMessage');
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

Route::post('checkAuthentication', function () {
    //dd( Auth::user() );
    return response()->json([
        'auth' => Auth::guard('guest')->check()
    ]);
    
});//->middleware('guest');


Auth::routes();
Auth::routes(['verify' => true]);

Route::get('/register', 'Auth\RegisterController@showRegistrationForm');

Route::post('/register', 'Auth\RegisterController@register')->name('register');

Route::get('/broadcast', function() {
         // New Pusher instance with our config data
    $pusher = new Pusher\Pusher(
        config('broadcasting.connections.pusher.key'),
        config('broadcasting.connections.pusher.secret'),
        config('broadcasting.connections.pusher.app_id'),
        config('broadcasting.connections.pusher.options')
    );
        
    // Enable pusher logging - I used an anonymous class and the Monolog
    $pusher->set_logger(new class {
           public function log($msg)
           {
                 \Log::info($msg);
           }
    });
        
    // Your data that you would like to send to Pusher
    $data = ['text' => 'hello world from Laravel 5.3'];
        
    // Sending the data to channel: "test_channel" with "my_event" event
    //$pusher->trigger( 'chat-room', 'ChatEvent', $data);
    $pusher->trigger( 'AdminSentMessageTo_nam', 'AdminSentMessage', $data);
        
    return 'ok'; 
});

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

