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
    
    }
}
