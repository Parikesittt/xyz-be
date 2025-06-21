<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase_order_details extends Model
{
    protected $guarded = [];
    protected $appends = ['attachment_type_name','location_name','itemcategory_name', 'warehouse_name_to'];

    public function atttachment_type() {
      return $this->belongsTo(Attachment_types::class);
    }
	public function itemcategory() {
      return $this->belongsTo(Itemcategorys::class);
    }
	public function location(){
        return $this->belongsTo(Locations::class);
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
	public function purchase_order() {
      return $this->belongsTo(Purchase_orders::class, 'purchase_order_id', 'id');
    }
	
	public function whs_to() {
      return $this->belongsTo(Warehouses::class, 'warehouse_id_to', 'id');
    }
	
	public function warehouse(){
        return $this->belongsTo(Warehouses::class);
    }
	
	
	public function getWarehouseNameToAttribute() {
       $warehouse = $this->whs_to()->first();
       return ($warehouse?$warehouse->name:null);
    }
	
	public function getAttachmentTypeNameAttribute() {
       $atttachment_type = $this->atttachment_type()->first();
       return ($atttachment_type?$atttachment_type->name:null);
    }
	public function getItemcategoryNameAttribute() {
       $itemcategory = $this->itemcategory()->first();
       return ($itemcategory?$itemcategory->name:null);
    }
	public function getLocationNameAttribute() {
       $location = $this->location()->first();
       return ($location?$location->name:null);
    }
}
