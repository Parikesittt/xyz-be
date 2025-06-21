<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suppliers extends Model
{
    protected $table = 'supplier';
    protected $guarded = [];
    protected $appends = ['location_name'];

    public function location() {
      return $this->belongsTo(Locations::class);
    }

	public function supplier_category_items(){
        return $this->hasMany(Supplier_category_items::class, 'supplier_id', 'id');
    }

	public function supplier_warehouses(){
        return $this->hasMany(Supplier_warehouses::class, 'supplier_id', 'id');
    }

  //------------------------------------------------------------------------------------
  // custom fields

    public function getLocationNameAttribute() {
       $location = $this->location()->first();
       return ($location?$location->name:null);
    }
}
