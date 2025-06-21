<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier_cashs extends Model
{
    protected $guarded = [];
    protected $appends = ['location_name'];

    public function location() {
        return $this->belongsTo(Locations::class);
    }
    
    public function purchase_request_months() {
        return $this->hasMany(Purchase_request_months::class, 'supplier_id', 'id');
    }

    public function getLocationNameAttribute() {
        $location = $this->location()->first();
        return ($location?$location->name:null);
    }
}
