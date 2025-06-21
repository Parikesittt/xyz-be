<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Min_assets extends Model
{
    protected $guarded = [];
    protected $appends = ['user_name'];

    public function user() {
      return $this->belongsTo(Users::class);
    }
	

	public function getUserNameAttribute() {
       $user = $this->user()->first();
       return ($user?$user->name:null);
    }
}
