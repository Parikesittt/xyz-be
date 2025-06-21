<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office_parts extends Model
{
    protected $table = 'office_part';
    protected $guarded = [];
    protected $appends = ['item_name'];

    public function itemName(){
		return $this->belongsTo(Items::class, 'item_id', 'id');
	}

	public function office_css() {
      return $this->belongsTo(Office_css::class, 'cs_id', 'id');
    }


	public function getItemNameAttribute() {
		$item_name = $this->itemName()->first();
		return ($item_name?$item_name->name:null);
	}
}
