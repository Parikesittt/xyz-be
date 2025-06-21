<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase_request_consolidation_details extends Model
{
    protected $guarded = [];

    public function purchase_request() {
      return $this->belongsTo(Purchase_requests::class, 'purchase_request_id', 'id');
    }
	public function purchase_request_consolidation() {
       return $this->belongsTo(Purchase_request_consolidations::class, 'purchase_request_consolidation_id', 'id');
    }
	
	public function getLocationNameAttribute() {
       $location = $this->location()->first();
       return ($location?$location->name:null);
    }
}
