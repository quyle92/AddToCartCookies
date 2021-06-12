<?php

namespace App\Http\Controllers;

use App\DeliveryMethod;
use App\Order;
use App\PayPal;
use App\PaymentMethod;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DB;
/**
 * Class PayPalController
 * @package App\Http\Controllers
 */
class PayPalController extends Controller
{   
    /**
     * @param Request $request
     */
    public function form(Request $request)
    {
        $order = Order::findOrFail(mt_rand(1, 140));

        // the above order is just for example.

        return view('form', compact('order'));
    }

    

    /**
     * @param $order_id
     * @param Request $request
     */ 
    public function checkout( Request $request)
    {
        $paypal = new PayPal;

        Session::flash('customer_name', $request['customer_name']);
        Session::flash('customer_email',$request['customer_email']);
        Session::flash('customer_phone', $request['customer_phone']);
        Session::flash('customer_address', $request['customer_address']);

        Session::keep(['products', 'shipping_fee', 'sub_total']);

        $response = $paypal->purchase([
            'amount' => Session::get('sub_total') + Session::get('shipping_fee'),
            'currency' => 'USD',
            'cancelUrl' => $paypal->getCancelUrl(),
            'returnUrl' => $paypal->getReturnUrl(),
        ]);

        if ($response->isRedirect()) {
            Session::flash('transaction_id', $response->getTransactionReference());
            $response->redirect();
        }

        return redirect()->back()->with([
            'message' => $response->getMessage(),
        ]);
    }

    /**
     * @param $order_id
     * @param Request $request
     * @return mixed
     */
    public function completed( Request $request)
    {
        
        $paypal = new PayPal;
        $response = $paypal->complete([
            'amount' =>  Session::get('sub_total') + Session::get('shipping_fee'),
            'currency' => 'USD',

        ]);

        if ($response->isSuccessful()) 
        {
             $transaction_id = Order::order_process('paypal');

             return response()->view('thankyou', [
                'transaction_id' => $transaction_id,
                'products' => Session::get('products')
             ]);
        }
        else
        {
            return redirect()->back()->with([
                'message' => $response->getMessage(),
             ]);
        }
        
    }

    /**
     * @param $order_id
     */
    public function cancelled($order_id)
    {
        $order = Order::findOrFail($order_id);

        return redirect()->route('order.paypal', encrypt($order_id))->with([
            'message' => 'You have cancelled your recent PayPal payment !',
        ]);
    }

        /**
     * @param $order_id
     * @param $env
     * @param Request $request
     */
    public function webhook($order_id, $env, Request $request)
    {
        // to do with new release of sudiptpa/paypal-ipn v3.0 (under development)
    }

    public function thankyou()
    {
        return    view('thankyou');
    }
}


/*
Note
 */
//Ref: https://artisansweb.net/paypal-payment-gateway-integration-in-php-using-paypal-rest-api/
//https://sujipthapa.co/blog/a-guide-to-integrate-omnipay-paypal-with-laravel