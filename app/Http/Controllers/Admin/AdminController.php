<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Pusher\Pusher;
use Auth;
class AdminController extends Controller
{   
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {   
    
        return view('admin.app');
    }

    public function test()
    {   
    
    }
}
