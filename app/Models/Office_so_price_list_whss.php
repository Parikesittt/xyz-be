<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office_so_price_list_whss extends Model
{
    protected $table = 'office_so_price_list_whs';
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
