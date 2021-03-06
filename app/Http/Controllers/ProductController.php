<?php

namespace App\Http\Controllers;

use App\Color;
use App\PaymentMethod;
use App\Product;
use App\Size;
use App\Style;
use Faker\Generator as Faker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;

class ProductController extends Controller
{
    private $cookie_name = 'shopping_cart';

    public function getAllProducts()
    {
        $products = Style::with([
            'colorsForOneStyleOnly' => function ($query) {
                $query->select('colors.id', 'color')->where('colors.id', 1);
        },
            'sizesForOneStyleOnly' => function ($query) {
                $query->select('sizes.id', 'size')->where('sizes.id', 1);
        }
        ])->get();

        return view('home')->with( compact('products') );
    }

    public function getSelectedStyle($id)
    {
        $product = Style::with([
            'colorsForOneStyleOnly' => function ($query) {
                $query->select('colors.id', 'color')->where('colors.id', 1);
        },
            'sizesForOneStyleOnly' => function ($query) {
                $query->select('sizes.id', 'size')->where('sizes.id', 1);
        }
        ])->where('id', $id)->first();

        $priceRange = json_encode( DB::table('products')
                     ->select(DB::raw('min(price), max(price)'))
                     ->where('style_id', $id)->first() );

        $sizes = Size::select('size')->get();

        $colors = Color::select('color')->get();
        $style_id = $product->colorsForOneStyleOnly[0]->pivot->style_id;

        $totalQuantity = intval(
            DB::table('products')
                    ->join('styles', 'styles.id', '=', 'products.style_id')->where('style_id', $style_id )
                    ->sum('quantity')
        );
        $style_id = $id;
        return view('product', compact('style_id'));
        //->with( compact('product',  'priceRange', 'sizes', 'colors', 'totalQuantity') );
    }

    public function getSelectedStyleSet( Request $request )
    {
        $style_id = $request->styleID;
        $size = $request->size;
        $color = $request->color;

        //this query is for learning only
        $priceQuantity = DB::table('products')
                //n???u ch??? c?? $size ko th?? ch???y c??i n??y
                ->when($size, function( $query, $size ){
                    return $query->join('sizes', 'products.size_id', '=', 'sizes.id')
                            ->where('size', $size);
                })
                //n???u ch??? c?? $color ko th?? ch???y c??i n??y
                ->when($color, function( $query, $color ){
                    return $query->join('colors', 'products.color_id', '=', 'colors.id')
                            ->where('color', $color);
                })
                //n???u  $size + $color ko c?? th?? ch???y c??i n??y th??i
                ->where('style_id', $style_id)->get();

        $priceQuantity = DB::table('products')
                ->join('sizes', 'products.size_id', '=', 'sizes.id')
                ->join('colors', 'products.color_id', '=', 'colors.id')
                ->join('styles', 'products.style_id', '=', 'styles.id')
                ->where('style_id', $style_id)
               // ->where('quantity', '<>', 0)
                ->select('style_id','style','fullNumber', 'color', 'size', 'quantity', 'price', 'picture')
                ->get();

        //dd($priceQuantity);
        return $priceQuantity;

    }

    public function addToCart(Request $request)
    {
        $item_data = json_encode( $request->all() );//dd( $item_data );

        //$cookie_value = [];

        if( ! isset ( $_COOKIE[$this->cookie_name] ) )
        {
            //$cookie_value[] = json_decode( $item_data );
            setcookie($this->cookie_name."[" . $request->fullNumber . "]", $item_data , time() + (86400 * 30), "/");
        }
        else
        {   //dd(json_decode( $_COOKIE[$this->cookie_name] ) );
            foreach ($_COOKIE[$this->cookie_name] as $name => $value) {

                if( $name == $request->fullNumber)
                {
                    setcookie($this->cookie_name."[" . $name . "]", $item_data , time() + (86400 * 30), "/");
                    break;
                }
                else
                {
                    setcookie($this->cookie_name."[" . $request->fullNumber . "]", $item_data , time() + (86400 * 30), "/");
                    break;
                }

            }
        }
        dd( ( $_COOKIE[$this->cookie_name] ) );

    }

    public function showCart()
    {
        return view('cart');
    }

    public function getVariationSet( Request $request )
    {
        $style_id = $request->styleID;

        $variationSet = DB::table('products')
            ->join('sizes', 'products.size_id', '=', 'sizes.id')
            ->join('colors', 'products.color_id', '=', 'colors.id')
            ->where('style_id', $style_id)->select('style_id','fullNumber', 'color', 'size', 'quantity', 'price')->get();
         //   dd($variationSet);

        return response([
             $style_id => $variationSet
        ], 200);
    }

    public function getMaxQuantityForEachItem( Request $request )
    {
        //if( ! $request->has('params') ) return;

        $fullNumberArr = explode(  ',' ,$request->query('params'));

        $maxQuantityArr = DB::table('products')->select('fullNumber', 'quantity')
                    ->whereIn('fullNumber', $fullNumberArr)
                    ->get();

        return response([
             'maxQuantityArr' => $maxQuantityArr
        ], 200);
    }

    public function checkout(Faker $faker )
    {
        $payment_methods = PaymentMethod::get('payment_type');

        return view( 'checkout', compact('faker', 'payment_methods') );
    }

    public function checkProducts(Request $request)
    {
        if( ! $request->products ) return;

        Session::flash('sub_total', $request->subTotal );
        Session::flash('products', $request->products );
        Session::keep(['shipping_fee']);

        $products =  $request->products;
        $out_of_stock = [];
        foreach( $products as &$item )
        {
            $ordered_item = Product::where('fullNumber', $item['fullNumber'])->first();
            $item['price'] = $ordered_item->price;

            if( empty( $ordered_item ) )
            {

                return response()->json([
                    'message' => [
                        'product_not_available' => `{$item['fullNumber']} is not available in our DB`
                    ],

                ]);
            }
            if( $item['quantity'] > $ordered_item->quantity )
            {
                $out_of_stock[] =  [
                    'fullNumber' => $item['fullNumber'],
                    'quantityLeft' => $ordered_item['quantity']
                ];

            }

        }

        if( count($out_of_stock) > 0 )
        {
            return response()->json([
                'message' => [
                    'out_of_stock' =>  $out_of_stock
                ],

            ]);

        }

        return response()->json([
            'message' => 'success'
        ]);
    }

    public function saveShippingFee(Request $request)
    {
        $shipping_fee = (float)  Input::get('shippingFee') ;
        Session::flash('shipping_fee', $shipping_fee );

    }

}

/*
Note
 */
        // Product::with(['prices' => function ($query) {
    //      $query->select('id', 'product_id', 'price', 'size')->where('size', 'M');
    //  }])->select('id', 'productName', 'picture')->get();//(1)
//(1) eager load: you must specify owner key + foreign key when eager loading specific columns of relation tables (but this requirement is not neccessary in case of pivot table).
//Ref: https://stackoverflow.com/a/53515281/11297747
//https://stackoverflow.com/a/47238258/11297747
//https://stackoverflow.com/a/16994569/11297747
//https://laracasts.com/discuss/channels/eloquent/eloquent-eager-loading-specific-columns
//(JarekTkaczyk  + stuartcusack answer)
// If you want to load  specific cols of parent relation table and takes only some cols of current table, remember to include the FK of current table in get() and primary key of relation table in with(), e.i User::with(['department:id,department_name', 'rejection:id,reason'])->orderBy('series_number')->get(['id','series_number', 'name', 'employee_id', 'email', 'created_at', 'last_login', 'department_id', 'rejection_id']); Otherwise, the relation will return null in the result.
