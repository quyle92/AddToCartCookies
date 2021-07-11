<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Guest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use DB; 
use App\Notifications\BroadcastNofitication;

class ChatController extends Controller
{   
    public function joinChat(Request $request) 
    {
        try 
        {  
            if( ! Auth::check() )
            {   

                $guest = new Guest();
                $guest->guest_name = $request->guest;
                $guest->save();

                Auth::logout();
                Auth::guard('guest')->login(  $guest ); 

   
            }

             return response()->json([
                'msg' => 'success',
                'guest' => Auth::guard('guest')->user()
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

            $guest = Auth::guard('guest')->user();
            $guest_id = $guest->id;
            $current_guest =  Guest::find( $guest_id ) ?? null;
            //dd($current_guest->chat);
            if( ! $current_guest->chat )
            {
                Chat::create([
                    'guest_id' => $guest_id ,
                    'messages' => array( $new_message )
                ]);
            }
            else 
            {  
                $current_messages = $current_guest->chat->messages;
                array_push($current_messages, $new_message);
                $current_guest->chat->messages = $current_messages;
                $current_guest->push();
            }
            
            
            event( new \App\Events\GuestSentMessage($guest, $new_message ) );
            User::first()->notify(new BroadcastNofitication($guest, $new_message));

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
            $message  = $request->message;
            $guest    = $request->guest;
            $guest_id = $request->guest_id;

            $new_message = array(
                'user' =>'admin',
                'content' => $request->message,
            ); 
            $current_guest =  Guest::findOrFail( $guest_id );
    
            $current_messages = $current_guest->chat->messages;
            array_push($current_messages, $new_message);
            $current_guest->chat->messages = $current_messages;
            $current_guest->push();

            event( new \App\Events\AdminSentMessage($message, $guest_id) );

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

    public function getGuestList() 
    {
        try 
        {   
            $guestList = Guest::with('chat')->get();//dd( $guestList );

            return response()->json([
                'result' => $guestList
            ]);
        }
        catch(\Exception $err) 
        {  
            return response()->json([
                'msg' => $err->getMessage()
            ]);
        }
    }

    public function markAsRead( Guest $guest)
    {   
        try 
        {      
            $guest->chat->is_read = 1;
            $guest->push(); 
            return response()->json([
                'result' => 'success'
            ]);
        }   
        catch(\Exception $err) 
        {  
            return response()->json([
                'msg' => $err->getMessage()
            ]);
        }
    }

    public function chatEnd( Guest $guest)
    {   
        try 
        {      
            $guest->chat->is_chat_end = 1;
            $guest->push(); 
            return response()->json([
                'result' => 'success'
            ]);
        }   
        catch(\Exception $err) 
        {  
            return response()->json([
                'msg' => $err->getMessage()
            ]);
        }
    }

    public function deleteChat(Request $request)
    {   
        try 
        {
            $guest = Guest::findOrFail( $request->guest_id );
            $guest->delete();

            return response()->json([
                'result' => 'success',
                'guest_deleted' => $request->guest_id
            ]);
        }
        catch(\Exception $err)
        {
            return response()->json([
                'msg' => $err->getMessage()
            ]);
        }   
    }

    public function deleteChatAll(Request $request)
    {   
        try 
        {
            DB::table('guests')->delete();

            return response()->json([
                'result' => 'success',
            ]);
        }
        catch(\Exception $err)
        {   
            return response()->json([
                'msg' => $err->getMessage(),
            ], 500);
        }   
    }


}

/**
 * Note
 */
