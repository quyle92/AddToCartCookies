<?php

namespace App;

use App\DeliveryMethod;
use App\Events\OrderCreated;
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

    
}
