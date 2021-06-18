<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BroadcastController extends Controller
{
    public function sendMessage(Request $request) 
    {
        //dump($request->post());
        return response()->json([
            'status' => 'success'
        ]);
    }
}
