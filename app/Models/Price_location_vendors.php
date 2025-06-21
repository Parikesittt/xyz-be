<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price_location_vendors extends Model
{
    protected $guarded = [];

    public function location() {
      return $this->belongsTo(Locations::class);
    }
	public function supplier() {
      return $this->belongsTo(Suppliers::class);
    }
	public function price_list_vendors(){
        return $this->hasMany(Price_list_vendors::class, 'price_list_id', 'id');
    }

    public function getLocationNameAttribute() {
       $location = $this->location()->first();
       return ($location?$location->name:null);
    }
	 public function getSupplierNameAttribute() {
       $supplier = $this->supplier()->first();
       return ($supplier?$supplier->name:null);
    }
	 public function getValidateDateFromNameAttribute() {
       $validate_date_from = $this->supplier()->first();
       return ($validate_date_from?$validate_date_from->validate_date_from:null);
    }
	 public function getValidateDateToNameAttribute() {
       $validate_date_to = $this->supplier()->first();
       return ($validate_date_to?$validate_date_to->validate_date_to:null);
    }
	public function getLocationCodeAttribute() {
       $location_code = $this->location()->first();
       return ($location_code?$location_code->code:null);
    }
}
