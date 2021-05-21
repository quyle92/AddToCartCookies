<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{	
	protected $guard = [];
	
    public function product()
    {
    	return $this->belongsTo(Product::class, 'product_id','id');
    }
}
