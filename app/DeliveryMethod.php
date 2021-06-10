<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryMethod extends Model
{
    protected $guarded = [];  
    const SHIPPING_FEE = 0;

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
