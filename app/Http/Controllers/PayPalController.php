<?php

namespace App\Http\Controllers;

use App\Order;
use App\PayPal;
use Illuminate\Http\Request;

/**
 * Class PayPalController
 * @package App\Http\Controllers
 */
class PayPalController extends Controller
{   
    private $payment_method_id = 3;//paypal
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
        //dd($request['products']);
        
        $request->session()->flash('customer_name', $request['clientInfo']['customer_name']);
        $request->session()->flash('customer_email',$request['clientInfo']['customer_email']);
        $request->session()->flash('customer_phone', $request['clientInfo']['customer_phone']);
        $request->session()->flash('customer_address', $request['clientInfo']['customer_address']);

        $product =  $request['products'];
        $out_of_stock = [];
        foreach ($products as &$item)
        {
            $ordered_item = Product::where('fullNumber', $item['fullNumber'])->first();
            $item['price'] = $ordered_item->price;
            
            if( empty( $ordered_item ) )
            {
                return redirect()->back()->with([
                    'error' => `{$item['fullNumber']} is not available in our DB`,
                ]);
            }
            if( $item['quantity'] > $ordered_item->quantity )
            {
                $out_of_stock =  $item['fullNumber'];

            }

        }

        if( count($out_of_stock) > 0 )
        {
           return redirect()->back()->with([
            'out_of_stock' => $out_of_stock,
            ]);
        }

        $request->session()->flash( 'products', $request['products'] );

        $response = $paypal->purchase([
            //'amount' => $paypal->formatAmount( $request->grandTotal ),
            'amount' =>10,
            'currency' => 'USD',
            'cancelUrl' => $paypal->getCancelUrl(),
            'returnUrl' => $paypal->getReturnUrl(),
        ]);

        if ($response->isRedirect()) {
            $response->redirect();
        }
        //dd($response->getMessage());
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
            'amount' => $paypal->formatAmount(20),
            'currency' => 'USD',

        ]);

        if ($response->isSuccessful()) {
           // dd($response);
            // $order->update([
            //     'transaction_id' => $response->getTransactionReference(),
            //     'payment_status' => Order::PAYMENT_COMPLETED,
            // ]);
            $order = Order::create([
                'transaction_id' => $response->getTransactionReference(),
                'customer_name' =>  $request->session()->get('customer_name'),
                'customer_email' => $request->session()->get('customer_email'),
                'customer_phone' => $request->session()->get('customer_phone'),
                'customer_address' => $request->session()->get('customer_address'),
                'payment_method_id' => $this->payment_method_id,
                'payment_status' => Order::PAYMENT_COMPLETED,
            ]);

            $products = $request->session()->get('products');
            $data = [];
            foreach($products as $item)
            {   
                $ordered_item = Product::where('fullNumber', $item['fullNumber'])->select('price','quantity');
                $data[] = [
                    'product_fullNumber'=>$item['fullNumber'],
                    //'order_id' => 
                    'unit_price' => $item['price'],
                    'order_quantity' => $item['quantity'],
                    'order_amount' => $item['price'] * $item['quantity'],
                ]; 
            }

            $order->order_details()->createMany($comment);
           
            return redirect()->route('app.checkout')->with([
                'message' => 'You recent payment is sucessful with reference code ' . $response->getTransactionReference(),
                'transaction_id' =>  $response->getTransactionReference()
            ]);
        }

        return redirect()->back()->with([
            'message' => $response->getMessage(),
        ]);
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