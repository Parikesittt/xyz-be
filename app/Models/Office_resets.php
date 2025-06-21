<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office_resets extends Model
{
    protected $table = 'office_reset';
    protected $guarded = [];
    protected $appends = ['item_name', 'warehouse_name'];

    public function itemName(){
		return $this->belongsTo(Items::class, 'item_id', 'id');
	}

	public function warehouse(){
        return $this->belongsTo(Warehouses::class);
    }

	public function office_reset_items(){
        return $this->hasMany(Office_reset_items::class, 'reset_id', 'id');
    }


	public function getItemNameAttribute() {
		$item_name = $this->itemName()->first();
		return ($item_name?$item_name->name:null);
	}

	public function getWarehouseNameAttribute() {
       $warehouse = $this->warehouse()->first();
       return ($warehouse?$warehouse->name:null);
    }


	public static function getNextCode() {

		$last_count = 1;

		$reset = Office_resets::select('code')
		->orderBy('code', 'desc')
		->first();

		if($reset) {
			$data = explode("RES", $reset->code);
			$last_count = intval($data[1]) + 1;
		}

		$curr_count = '';
		$curr_count = sprintf('%05d', $last_count);
		$COUNTER = $curr_count;

		return "RES".$COUNTER;
	}
}
