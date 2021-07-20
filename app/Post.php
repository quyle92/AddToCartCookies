<?php

namespace App;

use App\Category;
use App\Comment;
use App\Tag;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $guarded = [];

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug( $this->attributes['title'] );
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

     /**
     * Get all of the post's comments.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Scope a query to only include popular users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePostsWithMostComments($query )
    {
        return $query->join('comments', 'posts.id', '=', 'comments.commentable_id')
            ->where('commentable_type','=', 'App\Post')->selectRaw('posts.id, count(*) as comments_number')->groupBy('posts.id')->take(4)->get();
    }



}
