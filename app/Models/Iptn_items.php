<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Iptn_items extends Model
{
    protected $table = 'iptn_item';
    protected $guarded = [];
    protected $appends = ['itemgroupnumber'];

    public function item() {
      return $this->belongsTo(Items::class);
    }

	public function supplier() {
    	return $this->belongsTo(Suppliers::class);
    }

	public function items(){
        return $this->hasMany(Items::class, 'item_code', 'code');
    }

	public function iptn() {
      return $this->belongsTo(Iptns::class, 'iptn_id', 'id');
    }

    public function getItemGroupNumberAttribute() {
       $groupname = $this->item()->first();
       return ($groupname?$groupname->itemgroupnumber:null);
    }
}
