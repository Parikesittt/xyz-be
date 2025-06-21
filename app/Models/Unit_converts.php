<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit_converts extends Model
{
    protected $guarded = [];

    public function items() {
        return $this->belongsTo(Items::class, 'item_code', 'code');
    }
      
    public static function getNextCounterLevel($code) {
  
        $last_count = 1;
  
        //$CODELOCATION = $location_id;
        
        // get last count
        $level = Unit_converts::select('level')
          ->where('level','=', $last_count)
          ->orderBy('level', 'desc')
          ->first();
  
  
        if($level) {
          $data = $level->level;
          $last_count = intval($data[0]) + 1;
        }
  
        $curr_count = '';
        $curr_count = sprintf(intval($last_count));
        $COUNTER = $curr_count;
  
        return $COUNTER;
    }
}
