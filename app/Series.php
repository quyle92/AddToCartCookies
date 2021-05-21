<?php

namespace App;

use App\Product;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    public function products(){
		return $this->hasMany(Product::class);
	}
}
