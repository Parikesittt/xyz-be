<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ledger_transaction_alls extends Model
{
    protected $guarded = [];
    protected $appends = ['location_name', 'user_name', 'warehouse_name'];

    public function location() {
      return $this->belongsTo(Locations::class);
    }

	public function warehouse() {
      return $this->belongsTo(Warehouses::class);
    }

	public function user() {
      return $this->belongsTo(Users::class);
    }


	public function getLocationNameAttribute() {
		$location = $this->location()->first();
		return ($location?$location->name:null);
	}

	public function getWarehouseNameAttribute() {
       $warehouse = $this->warehouse()->first();
       return ($warehouse?$warehouse->name:null);
    }

	public function getUserNameAttribute() {
       $user = $this->user()->first();
       return ($user?$user->name:null);
    }
}
