<?php

namespace App;

use App\DeliveryMethod;
use App\Order;
use App\PaymentMethod;
use App\Product;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class Order extends Model
{
    const PAYMENT_COMPLETED = 1;
    const PAYMENT_PENDING = 0;

    /**
     * @var string
     */
    protected $table = 'orders';

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var array
     */
    protected $guarded = [];

    public function products()
    {
        return $this->belongsToMany(Products::class, 'order_details');
    }

    public function delivery_methods()
    {
        return $this->belongsTo(DeliveryMethod::class);
    }

    public function payment_methods()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function order_details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public static function order_process( $payment_method )
    { 
        try
        {
            $payment_method_id =  PaymentMethod::where('payment_type', 'paypal')->pluck('id') ;
            $payment_method_id = reset( $payment_method_id );

            if( $payment_method === 'cod')
            {   
               $transaction_id = Str::generateCODTransactionID();
            }
            
            if( $payment_method === 'paypal' )
            {
                $transaction_id = 'PPL_' . Session::get('transaction_id');
            }
           

            $order = Order::create([
                'transaction_id' =>  $transaction_id,
                'customer_name' =>  Session::get('customer_name'),
                'customer_email' => Session::get('customer_email'),
                'customer_phone' => Session::get('customer_phone'),
                'customer_address' => Session::get('customer_address'),
                'payment_method_id' => reset( $payment_method_id ),
                'subtotal' => Session::get('grand_total') + DeliveryMethod::SHIPPING_FEE,
                'grand_total' => Session::get('grand_total'),
                'payment_status' => Order::PAYMENT_COMPLETED,
            ]);

            $products = Session::get('products');
            $data = [];
            foreach($products as $item)
            {   
                $ordered_item = Product::where('fullNumber', $item['fullNumber'])->select('price','quantity');
                $data[] = [
                    'product_fullNumber'=> $item['fullNumber'],
                    //'order_id' => 
                    'unit_price' => $item['price'],
                    'order_quantity' => $item['quantity'],
                    'order_amount' => $item['price'] * $item['quantity'],
                ]; 

                DB::table('products')
                ->where('fullNumber', $item['fullNumber'])
                ->decrement('quantity', $item['quantity']);
            }

            $order->order_details()->createMany($data);

            return $transaction_id;

            
        }
        catch (Exception $e) {
            report($e);

            return false;
        }
        
    }
}
