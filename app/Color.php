<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $guarded = [];


    public function colors()
    {
        return $this->belongsToMany(Color::class, 'products')->withPivot('fullNumber', 'productName','picture', 'quantity', 'price');
    }
    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'products')->withPivot('fullNumber', 'productName','picture', 'quantity', 'price');
    }
}
