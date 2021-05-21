<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{	
	private $cookie_name = 'shopping_cart';

    public function getAllProducts()
    {
    	$products = 
  		Product::with(['prices' => function ($query) {     
    		$query->select('id', 'product_id', 'price', 'size')->where('size', 'M'); 
    	}])->select('id', 'productName', 'picture')->get();//(1)

    	return view('home')->with( compact('products') );
    }

    public function getselectedProduct($id)
    {
    	$product = 
    	Product::where('id', $id)->with(['prices' => function ($query) {     
    		$query->select('id', 'product_id', 'price', 'size'); 
    	}])->select('id', 'productName', 'picture', 'fullNumber')->first();//(1)

    	return view('product')->with( compact('product') );
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
    	{	//dd(json_decode( $_COOKIE[$this->cookie_name] ) );
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
    	$products = $_COOKIE[$this->cookie_name];

    	return view('cart')->with( compact('products') );
    }
}


/*
Note
 */
//(1) eager load: you must specify owner key + foreign key when eager loading specific columns of relation tables (but this requirement is not neccessary in case of pivot table)
//Ref: https://stackoverflow.com/a/53515281/11297747
//https://stackoverflow.com/a/47238258/11297747
