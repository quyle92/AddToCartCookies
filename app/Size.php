<?php

namespace App;

use App\Product;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{	
	protected $guarded = [];

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'products')->withPivot('fullNumber', 'productName','picture', 'quantity', 'price');
    }

    public function styles()
    {
        return $this->belongsToMany(Style::class, 'products')->withPivot('fullNumber', 'productName','picture', 'quantity', 'price');
    }

    
    
}
