<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{    
     protected $table = "chat";
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['guest_id', 'messages'];

    protected $casts = [
        'messages' => 'array'
    ];


}
