<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Canvas_sheet_items extends Model
{
    protected $guarded = [];
    protected $appends = ['location_name', 'warehouse_name'];

    public function location(){
        return $this->belongsTo(Locations::class);
    }
	public function warehouse(){
        return $this->belongsTo(Warehouses::class);
    }
	public function canvas_sheet() {
      return $this->belongsTo(Canvas_sheets::class, 'canvas_sheet_id', 'id');
    }

	public function getLocationNameAttribute() {
       $location = $this->location()->first();
       return ($location?$location->name:null);
    }
	public function getWarehouseNameAttribute() {
       $warehouse = $this->warehouse()->first();
       return ($warehouse?$warehouse->name:null);
    }
}
