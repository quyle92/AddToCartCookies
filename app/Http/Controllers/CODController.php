<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CODController extends Controller
{   
    private $required_info = ['customer_name', 'customer_email', 'customer_phone', 'customer_address'];

    public function codCheckOut(Request $request)
    {   
        if( ! $request->post() ) return;

        $this->processMissingFields($request);

        $transaction_id = Order::order_process('cod');

        return response()->view('thankyou', [
            'transaction_id' => $transaction_id,
            'products' => Session::get('products')
        ]);
    }

    protected function processMissingFields( Request $request ) 
    {
        $missing_field = [];
        $customer_info = $request->post();
        array_shift($customer_info);

        //customer_name, customer_email, customer_phone, customer_address
        foreach( $customer_info as $k => $v )
        {   
            if( in_array($k, $this->required_info) && $v === null ) {
               $missing_field[] = $k; 
            }   

            if( in_array($k, $this->required_info) ) {
                Session::flash($k, $v);
            }
            
        }

        if( count($missing_field) > 0 ) {  
            Session::flash('msg', ($missing_field) );
            return redirect()->back();
            
        }

    }
}
