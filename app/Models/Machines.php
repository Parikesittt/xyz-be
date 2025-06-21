<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Machines extends Model
{
    protected $guarded = [];

    public function machine_items(){
        return $this->hasMany(Machine_items::class, 'machine_id', 'id')
					 ->select()
					 ->orderByRaw('id asc');
    }		
}
