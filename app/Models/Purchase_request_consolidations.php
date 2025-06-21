<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase_request_consolidations extends Model
{
    protected $guarded = [];
    protected $appends = ['location_name','supplier_name','itemcategory_name','warehouse_name'];

    public function itemcategory() {
      return $this->belongsTo(Itemcategorys::class);
    }
	public function location(){
        return $this->belongsTo(Locations::class);
    }
	public function warehouse(){
        return $this->belongsTo(Warehouses::class);
    }
	public function supplier(){
        return $this->belongsTo(Suppliers::class);
    }
	public function itemcategorys(){
        return $this->hasMany(Itemcategorys::class, 'purchase_request_id', 'id');
    }
	public function subcategorys(){
        return $this->hasMany(Subcategorys::class, 'purchase_request_id', 'id');
    }
	public function items(){
        return $this->hasMany(Items::class, 'purchase_request_id', 'id');
    }
	public function purchase_request_month(){
        return $this->hasMany(Purchase_request_months::class, 'purchase_request_consolidation_id', 'id');
    }
	public function purchase_request() {
      return $this->belongsTo(Purchase_requests::class, 'purchase_request_id', 'id');
    }
	public function purchase_request_consolidation_details(){
        return $this->hasMany(Purchase_request_consolidation_details::class, 'purchase_request_consolidation_id', 'id');
	}
	

	public function getItemcategoryNameAttribute() {
       $itemcategory = $this->itemcategory()->first();
       return ($itemcategory?$itemcategory->name:null);
    }
	public function getLocationNameAttribute() {
       $location = $this->location()->first();
       return ($location?$location->name:null);
    }
	public function getSupplierNameAttribute() {
       $supplier = $this->supplier()->first();
       return ($supplier?$supplier->name:null);
    }
	public function getWarehouseNameAttribute() {
       $warehouse = $this->warehouse()->first();
       return ($warehouse?$warehouse->name:null);
    }
}
