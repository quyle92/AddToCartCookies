<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Pusher\Pusher;
class AdminController extends Controller
{
    public function index()
    {   
        
        return view('admin.app');
    }

    public function test()
    {   
         // New Pusher instance with our config data
    $pusher = new Pusher(
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
    $pusher->trigger( 'chat-room-1', 'ChatEvent', $data);
        
    return 'ok'; 
    }
}
