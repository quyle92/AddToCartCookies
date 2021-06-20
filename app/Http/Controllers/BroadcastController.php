<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BroadcastController extends Controller
{
    public function guestSentMessage(Request $request) 
    {
        try 
        {   
            $message = $request->message;
            $guest    = $request->guest;

            event( new \App\Events\ChatEvent($message, $guest) );

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
