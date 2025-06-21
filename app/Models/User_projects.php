<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_projects extends Model
{
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

      public function getItemGroupNumberAttribute() {
        $groupname = $this->item()->first();
        return ($groupname?$groupname->itemgroupnumber:null);
     }
}
