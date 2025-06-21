<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dashboard_rl_dimentions extends Model
{
    protected $guarded = [];
    protected $appends = ['user_name', 'company_code'];

    public function user() {
      return $this->belongsTo(Users::class);
    }
	
	public function company() {
      return $this->belongsTo(Companys::class, 'company_id', 'id');
    }
	
	
	public function getUserNameAttribute() {
       $user = $this->user()->first();
       return ($user?$user->name:null);
    }
	
	public function getCompanyCodeAttribute() {
       $company = $this->company()->first();
       return ($company?$company->code:null);
    }
}
