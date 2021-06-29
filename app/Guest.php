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

    protected $hidden = ['guest_name','updated_at', 'created_at'];
    
    protected $appends = ['name'];//(1)

    public function chat() {
        return $this->hasOne(Chat::class);
    }

    public function getNameAttribute()
    {
        return $this->guest_name ;//(1)
    }
}

/**
 * Note
 */
//(1): this is to set addition alias column name. Ref: https://stackoverflow.com/a/55527816/11297747, https://stackoverflow.com/a/35701539/11297747