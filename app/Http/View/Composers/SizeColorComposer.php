<?php

namespace App\Http\View\Composers;

use App\Color;
use App\Size;
use Illuminate\View\View;

class SizeColorComposer
{
    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    protected $variations = [];

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct(Size $sizes, Color $colors)
    {
        // Dependencies automatically resolved by service container...
        $this->variations = $sizes;
        $this->variations = $colors;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {	
        if( ! session('sizes') || ! session('colors'))
        {  
            $sizes = Size::select('size')->get();
            $sizeList = [];
            foreach($sizes as $size){
                $sizeList[] = $size->size;
            }
            session(['sizes' =>  $sizeList ]);

            $colors = Color::select('color')->get();
            $colorList = [];
            foreach($colors as $color){
                $colorList[] = $color->color;
            }
            session(['colors' =>  $colorList ]);
        }

    	$sizes = session('sizes');
        $colors = session('colors');

            
        $view->with('sizes' , json_encode($sizes)   );
        $view->with('colors', json_encode($colors)  );
    }
}