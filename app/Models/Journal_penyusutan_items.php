<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journal_penyusutan_items extends Model
{
    protected $guarded = [];
    protected $appends = ['warehouse_name', 'location_name'];

    public function location() {
      return $this->belongsTo(Locations::class);
    }
	
	public function warehouse() {
      return $this->belongsTo(Warehouses::class);
    }
  
	public function getWarehouseNameAttribute() {
       $warehouse = $this->warehouse()->first();
       return ($warehouse?$warehouse->name:null);
    }
	public function getLocationNameAttribute() {
       $location = $this->location()->first();
       return ($location?$location->name:null);
    }
}
