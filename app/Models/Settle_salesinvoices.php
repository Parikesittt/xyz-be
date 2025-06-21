<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settle_salesinvoices extends Model
{
    protected $guarded = [];

    public static function getNextCounterSSId() {

      

      //$last_count = 1;
 
      // get last count
      $settle = Settle_salesinvoices::select('code')
        ->orderBy('code', 'desc')
        ->first();

      if($settle) {
        //$data = ($settle->code);
        $last_count = $settle->code + 1;
      }else{
		$last_count = 1;  
	  }

     // $curr_count = '';
     // $curr_count = sprintf($curr_count + intval($last_count));
      $COUNTER = $last_count;

      return $COUNTER;
    }
}
