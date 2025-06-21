<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{
    protected $table = 'location';
    protected $guarded = [];
    protected $appends = ['company_code'];

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

	public function company() {
      return $this->belongsTo(Companys::class);
    }


	public function getCompanyCodeAttribute() {
       $company = $this->company()->first();
       return ($company?$company->code:null);
    }
}
