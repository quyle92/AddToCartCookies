<?php
use Illuminate\Support\Facades\Auth;
use App\Guest;
use App\User;
/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Route::post('/broadcasting/auth/admin', function (Illuminate\Http\Request $req) {

    $user = User::findOrFail(27);
    
       Auth::login( $user );
// dd(Auth::check());
    return Broadcast::auth($req);
    

})->middleware('guest');

Route::post('/broadcasting/auth/guest', function (Illuminate\Http\Request $req) {
    
    return Broadcast::auth($req);

})->middleware('guest');

Broadcast::channel('GuestSentMessage', function ($user) {
    // dD($user);
    return 'admin';
});

Broadcast::channel('AdminSentMessageTo_{guest}', function ($user, $guest) {
    // dump($user);
    // dd($guest);
    return $guest;
});

/*
Note
 */
//https://stackoverflow.com/questions/43341820/laravel-echo-allow-guests-to-connect-to-presence-channel
//https://stackoverflow.com/questions/47057760/laravel-broadcast-authentication

