<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Guest extends Authenticatable
{
  
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard = [];
    
    public function chat() {
        return $this->hasOne(Chat::class);
    }
}
