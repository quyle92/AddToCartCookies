<?php

namespace App;

use App\Series;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{	

	protected $guarded = [];  

	protected static function boot ()
	{	
		parent::boot();//(1)

		parent::creating( function( $model ) {
			$model->number = Product::where('series_id', $model->series_id)->max('number') + 1;
			$model->fullNumber = $model->series->prefix . '-' . str_pad($model->number, 3, '0', STR_PAD_LEFT);
		});

	}

	public function series(){
		return $this->belongsTo(Series::class);
	}


    public function prices()
    {
    	return $this->hasMany(Price::class, 'product_id','id');
    }
}

/*
Note
 */
//(1) remember to put this line, else error : ErrorException: Undefined index /laravel/framework/src/Illuminate/Database/Eloquent/Model.php on line 241