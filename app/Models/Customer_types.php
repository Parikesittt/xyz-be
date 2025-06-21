<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer_types extends Model
{
    protected $guarded = [];

    public function list_group(){
        return $this->hasMany(Customer_type_groups::class, 'type_id', 'id');
    }
}
