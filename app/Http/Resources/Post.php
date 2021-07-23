<?php

namespace App\Http\Resources;

use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\User as UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class Post extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'summary' => $this->summary,
            'views' => $this->views,
            'likes' => $this->likes,
            'dislikes' => $this->dislikes,
            'category' =>  new CategoryResource($this->category),
            'user' =>  new UserResource($this->user),

        ];
    }
}

//Good ex of API resource: https://blog.pusher.com/build-rest-api-laravel-api-resources/
