<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ChatController extends Controller
{   
    public function joinChat(Request $request) 
    {
        try 
        {   
            $guest = new Guest();
            $guest->guest_name = $request->guest;
            $guest->save();

            Auth::login( $guest );
            Session::put('guest', $guest);
            return response()->json([
                'msg' => 'success'
            ]);
        }
        catch(\Exception $err) 
        {  
            return response()->json([
                'msg' => $err->getMessage()
            ]);
        }
    }

    public function guestSentMessage(Request $request) 
    {
        try 
        {   
            $new_message = array(
                    'user' =>'guest',
                    'content' => $request->message
            );
            $guest = session('guest');
            dd(  Auth::user() );
            $current_guest =  Chat::find( $guest->id ) ?? null;
            if( ! $current_guest )
            {
                Chat::create([
                    'guest_id' => $guest->id ,
                    'messages' => $new_message
                ]);
            }
            else 
            {
                $current_messages = $current_guest->chat->messages;
                array_push($current_messages);
                $current_guest->messages = $current_messages;

            }
            
            
            event( new \App\Events\GuestSentMessage( $new_message, $guest ) );

            return response()->json([
                'msg' => 'success'
            ]);
        }
        catch(\Exception $err) 
        {  
            return response()->json([
                'msg' => $err->getMessage()
            ]);
        }
        
    }

    public function adminSentMessage(Request $request) 
    {
        try 
        {   
            $message = $request->message;
            $guest    = $request->guest;
            event( new \App\Events\AdminSentMessage($message, $guest) );

            return response()->json([
                'msg' => 'success'
            ]);
        }
        catch(\Exception $err) 
        {  
            return response()->json([
                'msg' => $err->getMessage()
            ]);
        }
        
    }


}
