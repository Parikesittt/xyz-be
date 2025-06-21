<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_apps extends Model
{
    protected $table = 'user_application';
	protected $primaryKey = 'id';
    protected $guarded = [];

    public function user() {
		return $this->belongsTo(Users::class);
	}

	public function users() {
		return $this->hasMany(Users::class, 'id', 'user_id');
	}
}
