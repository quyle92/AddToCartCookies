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
    protected $fillable = ['guest_id', 'messages', 'is_chat_end', 'is_read'];

    protected $visible = ['messages', 'is_chat_end', 'is_read'];

    protected $casts = [
        'messages' => 'array'
    ];


}
