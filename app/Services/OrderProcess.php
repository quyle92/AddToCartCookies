<?php 
namespace App\Service;

use App\DeliveryMethod;
use App\Events\OrderCreated;
use App\Order;
use App\PaymentMethod;
use App\Product;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class OrderProcess 
{
	public function order_process( $payment_method )
    { 
        try
        { 
            $payment_method_id =  PaymentMethod::where('payment_type', $payment_method )->pluck('id') ;
            $payment_method_id = reset( $payment_method_id );

            if( $payment_method === 'cod')
            {  
               $transaction_id = Str::generateCODTransactionID();
            }
            
            if( $payment_method === 'paypal' )
            {
                $transaction_id = 'PPL_' . Session::get('transaction_id');
            }
          
            $grand_total = Session::get('sub_total') + Session::get('shipping_fee');
            Session::flash('grand_total', $grand_total );

            $order = Order::create([
                'transaction_id' =>  $transaction_id,
                'customer_name' =>  Session::get('customer_name'),
                'customer_email' => Session::get('customer_email'),
                'customer_phone' => Session::get('customer_phone'),
                'customer_address' => Session::get('customer_address'),
                'payment_method_id' => reset( $payment_method_id ),
                'sub_total' => Session::get('sub_total'),
                'shipping_fee' => Session::get('shipping_fee'),
                'grand_total' => $grand_total,
                'payment_status' => Order::PAYMENT_COMPLETED,
            ]);

            $products = Session::get('products');
            $data = [];
            foreach($products as $item)
            {   
                $ordered_item = Product::where('fullNumber', $item['fullNumber'])->select('price','quantity');
                $data[] = [
                    'product_fullNumber'=> $item['fullNumber'],
                    'unit_price' => $item['price'],
                    'order_quantity' => $item['quantity'],
                    'order_amount' => $item['price'] * $item['quantity'],
                ]; 

                DB::table('products')
                ->where('fullNumber', $item['fullNumber'])
                ->decrement('quantity', $item['quantity']);
            }

            $order->order_details()->createMany($data);

            event( new OrderCreated($order) );
           
            return $transaction_id;

            
        }
        catch (Exception $e) {
            report($e);

            return false;
        }
        
    }
}
?>