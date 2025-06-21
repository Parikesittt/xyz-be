<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journals extends Model
{
    protected $guarded = [];

    public function price_lists() {
      return $this->hasMany(Price_lists::class, 'location_id', 'id');
    }
    public function users() {
      return $this->hasMany(Users::class, 'location_id', 'id');
    }
    public function purchase_requests() {
      return $this->hasMany(Purchase_requests::class, 'location_id', 'id');
    }
  
    public function inventorys() {
      return $this->hasMany(Inventorys::class, 'location_id', 'id');
    }
    public function warehouses() {
      return $this->hasMany(Warehouses::class, 'location_id', 'id');
    }
	
	public function transactions() {
      return $this->hasMany(Transactions::class, 'department_id', 'id');
    }
}
