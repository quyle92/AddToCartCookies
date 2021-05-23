<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Style extends Model
{		
	protected $guarded = [];

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'products')->withPivot('fullNumber', 'quantity');
    }

    public function colorsForOneStyleOnly()
    {
        return $this->belongsToMany(Color::class, 'products')->withPivot('fullNumber', 'quantity')->wherePivot('color_id', 1)->wherePivot('size_id', '=', 1);
    }//(1)

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'products')->withPivot('fullNumber', 'quantity');
    }

    public function sizesForOneStyleOnly()
    {
        return $this->belongsToMany(Size::class, 'products')->withPivot('fullNumber', 'quantity')->wherePivot('color_id', 1)->wherePivot('size_id', '=', 1);
    }//(1)
}

/*
Note
 */
//(1) cần phải làm vậy để: khi call getAllProducts(), thì pivot sẽ ko lấy tất cả style_id (biết cái này nhờ dùng debugbar) (wherePivot trong https://laravel.com/docs/5.8/eloquent-relationships).
//=> cái này for learning purpose only vì khi check lại trong debugbar thì query time lại > so với khi dùng colors() và sizes()
