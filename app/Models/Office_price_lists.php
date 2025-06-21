<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office_price_lists extends Model
{
    protected $table = 'office_price_list';
    protected $guarded = [];
    protected $appends = ['item_name'];

    public function itemName(){
		return $this->belongsTo(Items::class, 'item_id', 'id');
	}

	public function office_price_list_items(){
        return $this->hasMany(Office_price_list_items::class, 'price_list_id', 'id');
    }

	public function office_price_list_whss(){
        return $this->hasMany(Office_price_list_whss::class, 'price_list_id', 'id');
    }


	public function getItemNameAttribute() {
		$item_name = $this->itemName()->first();
		return ($item_name?$item_name->name:null);
	}


	public static function getNextCode() {

		$last_count = 1;

		$price_list = Office_price_lists::select('code')
		->orderBy('code', 'desc')
		->first();

		if($price_list) {
			$data = explode("PL", $price_list->code);
			$last_count = intval($data[1]) + 1;
		}

		$curr_count = '';
		$curr_count = sprintf('%05d', $last_count);
		$COUNTER = $curr_count;

		return "PL".$COUNTER;
	}
}
