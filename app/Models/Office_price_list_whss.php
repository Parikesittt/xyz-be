<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office_price_list_whss extends Model
{
    protected $guarded = [];
    protected $appends = ['warehouse_name', 'warehouse_code'];

    public function warehouse() {
      return $this->belongsTo(Warehouses::class);
    }
	
	
	public function getWarehouseCodeAttribute() {
       $warehouse = $this->warehouse()->first();
       return ($warehouse?$warehouse->code:null);
    }
	
	public function getWarehouseNameAttribute() {
       $warehouse = $this->warehouse()->first();
       return ($warehouse?$warehouse->name:null);
    }
}
