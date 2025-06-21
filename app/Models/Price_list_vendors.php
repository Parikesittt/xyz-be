<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price_list_vendors extends Model
{
    protected $guarded = [];
    protected $appends = ['itemgroupnumber'];

    public function item() {
      return $this->belongsTo(Items::class);
    }
	
	public function items(){
        return $this->hasMany(Items::class, 'item_code', 'code');
    }

    public function getItemGroupNumberAttribute() {
       $groupname = $this->item()->first();
       return ($groupname?$groupname->itemgroupnumber:null);
    }
}
