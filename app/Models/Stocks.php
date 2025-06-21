<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stocks extends Model
{
    protected $guarded = [];
    protected $appends = ['location_name', 'warehouse_name', 'warehouse_code', 'warehouse_central_type'];

    public function items() {
      return $this->hasMany(Items::class, 'unit_id', 'id');
    }
	
	public function location() {
      return $this->belongsTo(Locations::class);
    }
	
	public function warehouse() {
      return $this->belongsTo(Warehouses::class);
    }

    public function getLocationNameAttribute() {
       $location = $this->location()->first();
       return ($location?$location->name:null);
    }
	
	public function getWarehouseNameAttribute() {
       $warehouse = $this->warehouse()->first();
       return ($warehouse?$warehouse->name:null);
    }
	
	public function getWarehouseCentralTypeAttribute() {
       $warehouse = $this->warehouse()->first();
       return ($warehouse?$warehouse->warehouse_central_type:null);
    }
	
	public function getWarehouseCodeAttribute() {
       $warehouse = $this->warehouse()->first();
       return ($warehouse?$warehouse->code:null);
    }
}
