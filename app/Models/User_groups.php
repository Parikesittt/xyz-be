<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_groups extends Model
{
    protected $guarded = [];

    public function users() {
    	return $this->hasMany(Users::class, 'user_group_id', 'id');
    }
}
