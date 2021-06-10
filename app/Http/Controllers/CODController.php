<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CODController extends Controller
{
    public function codCheckOut(Request $request)
    {   
        Session::flash('customer_name', $request['customer_name']);
        Session::flash('customer_email',$request['customer_email']);
        Session::flash('customer_phone', $request['customer_phone']);
        Session::flash('customer_address', $request['customer_address']);

        $transaction_id = Order::order_process('cod');

        return response()->view('thankyou', [
            'transaction_id' => $transaction_id,
            'products' => Session::get('products')
             ]);
    }
}
