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

    public function guestUpdate(Request $request) 
    {   
        try 
        {  
            event( new \App\Events\GuestUpdate( $request->guest) );

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
