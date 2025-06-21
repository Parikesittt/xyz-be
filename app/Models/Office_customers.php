<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office_customers extends Model
{
    protected $guarded = [];

    public function list_item(){
		return $this->hasMany(Office_customer_items::class,'customer_id','id')->orderBy('id','asc');
	}

	public static function getNextCode() {

		$last_count = 1;

		$customer = Office_customers::select('code')
		->orderBy('code', 'desc')
		->first();

		if($customer) {
			$data = explode("Z", $customer->code);
			$last_count = intval($data[1]) + 1;
		}

		$curr_count = '';
		$curr_count = sprintf('%06d', $curr_count + intval($last_count));
		$COUNTER = $curr_count;

		return "Z".$COUNTER;
	}
}
