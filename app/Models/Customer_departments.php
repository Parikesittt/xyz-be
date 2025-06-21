<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer_departments extends Model
{
    protected $table = 'customer_department';
    protected $guarded = [];

    public static function getNextCode() {

      $last_count = 1;

      $num = Customer_departments::select('id')
      ->orderBy('id', 'desc')
      ->first();

      if($num) {
        $data = $num->id;
        $last_count = intval($data) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%02d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $COUNTER;
    }
}
