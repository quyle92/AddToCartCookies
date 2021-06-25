<?php

namespace App\Http\Controllers;

use App\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

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
            $message = $request->message;
            $guest    = $request->guest;
        
            
            event( new \App\Events\GuestSentMessage( $message, $guest ) );

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
