<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office_cost_items extends Model
{
    protected $table = 'office_cost_item';
    protected $guarded = [];

    public function item() {
      return $this->belongsTo(Items::class);
    }

	public function items(){
        return $this->hasMany(Items::class, 'item_code', 'code');
    }
}
