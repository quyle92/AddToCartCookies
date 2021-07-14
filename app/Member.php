<?php

namespace App;

use App\Comment;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable
{
    protected $guard = [];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
