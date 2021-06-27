<?php
use Illuminate\Support\Facades\Auth;
use App\Guest;
use App\User;
use Illuminate\Support\Facades\Session;
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

    return Broadcast::auth($req);
    

});//(2)

// Route::post('/broadcasting/auth/guest', function (Illuminate\Http\Request $req) {
// // dd( Session::all() );
//     return Broadcast::auth($req);

// });



Broadcast::channel('guest-sent-message', function ($user) {
// dd($user);
    return  true;
});

Broadcast::channel('admin-whisper-to-{guest}', function ($user, $guest) {
// dd($user);
    return  true;
});

Broadcast::channel('admin-sent-message-{guest}', function ( $user, $guest) {

    return  true;
});//(1)






/*
Note
 */
//https://stackoverflow.com/questions/43341820/laravel-echo-allow-guests-to-connect-to-presence-channel
//https://stackoverflow.com/questions/47057760/laravel-broadcast-authentication
//(1)"Too few arguments to function App\Providers\BroadcastServiceProvider::{closure}(), 1 passed in F:\webdocs\test\AddToCartCookies\vendor\laravel\framework\src\Illuminate\Broadcasting\Broadcasters\Broadcaster.php on line 77 "
//==>cách debug: 
//a/ vào console: check  "Pusher :  : [{"type":"PusherError","data":{"code":null,"message":"Cannot broadcast client event (connection not subscribed to channel private-admin-sent-message_)"}}]" ta thấy "private-admin-sent-message_" là channel mà đang connect bị failed.
//b/ Check xem channel failed đó là ở đâu gọi. VD: nó sẽ nằm ở Chat.vue chỗ "Echo.private(`admin-sent-message_${this.$store.state.guest}`)",  ở đó ta thấy  channel có 1 cái variable mà trong chỗ console báo lỗi channel lại thiếu đi variable này =>lỗi nằm ở channel truyền lên bị thiếu variable

//(2) Route::post only run in case of private channel, which means there is "Echo.private()..." somewhere in Javascript and it only run for client Event (event trigger in JavaScript), not channel event (event triggered in backend code,ie. event( new \App\Events\GuestSentMessage( $message, $guest ) );

//Console displays this msg: "["No callbacks on private-admin-sent-message_nam for App\\Events\\AdminSentMessage"]": có nghĩa là ở trong Javascript, mình có subscribe to Channel nhưng ko listen, vì dụ như khi whisper chẳng hạn, ei. Echo.private(...).whisper, mà whisper làm gì có callback. Khi Echo.private(...).listen(Event, () => {}), tức có callback thì msg đó sẽ có display nữa. TH2 là có listen nhưng cái listen đó nó nằm trong 1 hàm mà hàm đó nó chưa chạy (ví dụ như do mệnh đề if chưa đc thỏa mãn nên hàm chứa Echo.private(...).listen đó chưa chạy, nên cho dù data có về dc client đi nữa thì nó cũng ko lấy data đó dc)

//["Event recd",{"event":"App\\Events\\AdminSentMessage","channel":"private-admin-sent-message-nam","data":{"message":"asas","guest":"nam"}}] ==> this is channel event

//Pusher :  ["Event sent",{"event":"client-typing","data":{"name":"admin","message":""},"channel":"private-admin-sent-message-nam"}] 
//==> this is client event 

