<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periodes extends Model
{
    protected $guarded = [];
    protected $appends = ['user_name', 'company_name'];

    public function user() {
      return $this->belongsTo(Users::class);
    }
	
	public function company() {
      return $this->belongsTo(Companys::class);
    }
	
	public function periode_items(){
        return $this->hasMany(Periode_items::class, 'periode_id', 'id');
    }
	
	
	public function getUserNameAttribute() {
       $user = $this->user()->first();
       return ($user?$user->name:null);
    }
	
	public function getCompanyNameAttribute() {
       $company = $this->company()->first();
       return ($company?$company->name:null);
    }
}
