<?php
namespace App\Mixin;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StrMixin 
{
    public function generateCODTransactionID()
    {   
         return  function () {
            $mydate = getdate(date("U"));
            $now = \DateTime::createFromFormat('U.u', microtime(true));
            $now = $now->format("m-d-Y H:i:s.u");

            $transaction_id = 'COD_' . $mydate['year'] .  ( $mydate['mon'] < 10 ? '0'.$mydate['mon'] : $mydate['mon'] ) . $mydate['mday'] . ( $mydate['hours'] < 10 ? '0'.$mydate['hours'] : $mydate['hours'] ) . ( $mydate['minutes'] < 10 ? '0'.$mydate['minutes'] : $mydate['minutes'] ) . ( $mydate['seconds'] < 10 ? '0'.$mydate['seconds'] : $mydate['seconds'] ) . '.' . Str::substr($now, 20, 7);

            return $transaction_id;
        }; //(1)
    }

}

/*
Note
 */
//(1): this  function ($tld) will be stored in $macro[] in Macroable.php