<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office_branchs extends Model
{
    protected $table = 'office_branch';
    protected $guarded = [];
    protected $appends = ['location_name'];

    public function location() {
	  return $this->belongsTo(Locations::class, 'location_id', 'id');
	}


	public function getLocationNameAttribute() {
       $location = $this->location()->first();
       return ($location?$location->name:null);
    }
}
